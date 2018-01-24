<?php

namespace training\modules\admin\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use training\modules\admin\models\Answer;
use training\modules\admin\models\Lesson;
use training\modules\admin\models\Question;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class QuestionsController
 *
 * @package training\modules\admin\controllers
 */
class QuestionsController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $isValid = parent::beforeAction($action);

        if (YII_DEBUG && \Yii::$app->user->isGuest) {
            \Yii::$app->user->login(User::findOne(['email' => 'guest.user@example.com']));
        }

        if (\Yii::$app->request->isPost) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['delete'],
            ]
        ];
        $behaviors['confirm'] = [
            'class' => ConfirmFilter::className(),
            'actions' => ['delete']
        ];
        $behaviors['ajax'] = [
            'class' => AjaxFilter::className(),
            'except' => ['index']
        ];

        return $behaviors;
    }

    /**
     * @param string $lesson_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($lesson_uuid)
    {
        $lesson = Lesson::findOne($lesson_uuid);

        if (!$lesson) {
            throw new HttpException(404, 'Lesson not found.');
        }

        $params = [
            'dataProvider' => Question::search($lesson_uuid),
            'lesson' => $lesson,
            'answers' => Answer::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('question_uuid')
                ->indexBy('question_uuid')->column(),
        ];

        return $this->render('index', $params);
    }

    /**
     * @param string $lesson_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($lesson_uuid)
    {
        $lesson = Lesson::findOne($lesson_uuid);

        if (!$lesson) {
            throw new HttpException(404, 'Lesson not found.');
        }

        $model = new Question([
            'lesson_uuid' => $lesson_uuid,
            'active' => true,
            'sort' => 100,
            'type' => Question::TYPE_DEFAULT
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
            'course_uuid' => $lesson->course_uuid
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var Question $model */
        $model = Question::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Question not found.');
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
            'course_uuid' => $model->lesson->course_uuid
        ]);
    }

    /**
     * @param string $uuid
     * @param bool $deep
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid, $deep = false)
    {
        /* @var Question $model */
        $model = Question::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Question not found.');
        }

        // Makes a model`s copy
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy, $deep ? $model : null);
        }

        return $this->renderPartial('copy', [
            'model' => $copy,
            'course_uuid' => $model->lesson->course_uuid
        ]);
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = Question::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param Question $model
     * @param Question $original
     * @return array
     */
    protected function postCreate($model, $original = null)
    {
        // Validate user inputs
        $errors = ActiveForm::validate($model);

        if ($errors) {
            \Yii::$app->response->statusCode = 206;
            return $errors;
        }

        $result = $model->save(false);

        if ($result && $original) {
            foreach ($original->answers as $answer) {
                $answer->question_uuid = $model->uuid;
                $answer->duplicate()->save();
            }
        }

        return $model->attributes;
    }
}
