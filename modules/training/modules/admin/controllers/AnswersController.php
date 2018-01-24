<?php

namespace training\modules\admin\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use training\modules\admin\models\Answer;
use training\modules\admin\models\Question;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class AnswersController
 *
 * @package training\modules\admin\controllers
 */
class AnswersController extends Controller
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
     * @param string $question_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($question_uuid)
    {
        $question = Question::findOne($question_uuid);

        if (!$question) {
            throw new HttpException(404, 'Question not found.');
        }

        $params = [
            'dataProvider' => Answer::search($question_uuid),
            'question' => $question,
        ];

        return $this->render('index', $params);
    }

    /**
     * @param string $question_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($question_uuid)
    {
        $question = Question::findOne($question_uuid);

        if (!$question) {
            throw new HttpException(404, 'Question not found.');
        }

        $model = new Answer([
            'question_uuid' => $question_uuid,
            'sort' => 100,
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var Answer $model */
        $model = Answer::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Answer not found.');
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid)
    {
        /* @var Answer $model */
        $model = Answer::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Answer not found.');
        }

        // Makes a model`s copy
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy);
        }

        return $this->renderPartial('copy', [
            'model' => $copy,
        ]);
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = Answer::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    protected function postCreate($model)
    {
        // Validate user inputs
        $errors = ActiveForm::validate($model);

        if ($errors) {
            \Yii::$app->response->statusCode = 206;
            return $errors;
        }

        $model->save(false);

        return $model->attributes;
    }
}
