<?php

namespace training\modules\admin\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use app\models\WorkflowStatus;
use training\modules\admin\components\traits\Duplicator;
use training\modules\admin\models\Course;
use training\modules\admin\models\Lesson;
use training\modules\admin\models\Question;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class LessonsController
 *
 * @package training\modules\admin\controllers
 */
class LessonsController extends Controller
{
    use Duplicator;

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
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['delete'],
            ]
        ];
        $behaviors['confirm'] = [
            'class' => ConfirmFilter::class,
            'actions' => ['delete']
        ];
        $behaviors['ajax'] = [
            'class' => AjaxFilter::class,
            'except' => ['index']
        ];

        return $behaviors;
    }

    /**
     * @param string $course_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($course_uuid)
    {
        $course = Course::findOne($course_uuid);

        if (!$course) {
            throw new HttpException(404, 'Training course not found.');
        }

        $params = [
            'dataProvider' => Lesson::search($course_uuid),
            'course' => $course,
            'questions' => Question::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('lesson_uuid')
                ->indexBy('lesson_uuid')->column(),
        ];

        return $this->render('index', $params);
    }

    /**
     * @param string $course_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($course_uuid)
    {
        $course = Course::findOne($course_uuid);

        if (!$course) {
            throw new HttpException(404, 'Training course not found.');
        }

        $model = new Lesson([
            'course_uuid' => $course_uuid,
            'active' => true,
            'sort' => 100
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
            'workflow' => new Workflow(['status' => WorkflowStatus::WORKFLOW_STATUS_DEFAULT])
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var Lesson $model */
        $model = Lesson::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Lesson not found.');
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
            'workflow' => $model->workflow ?: new Workflow(['status' => WorkflowStatus::WORKFLOW_STATUS_DEFAULT])
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
        /* @var Lesson $model */
        $model = Lesson::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Lesson not found.');
        }

        // Makes a model`s copy
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy, $deep ? $model : null);
        }

        return $this->renderPartial('copy', [
            'model' => $copy,
            'workflow' => new Workflow(['status' => WorkflowStatus::WORKFLOW_STATUS_DEFAULT])
        ]);
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = Lesson::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param Lesson $model
     * @param Lesson $original
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
            foreach ($original->questions as $question) {
                $this->duplicateQuestion($question, $model->uuid);
            }
        }

        return $model->attributes;
    }

}
