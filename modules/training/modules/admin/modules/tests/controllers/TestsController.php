<?php

namespace training\modules\admin\modules\tests\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use app\models\WorkflowStatus;
use training\modules\admin\models\Attempt;
use training\modules\admin\models\Course;
use training\modules\admin\models\Test;
use training\modules\admin\models\TestQuestion;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class TestsController
 *
 * @package training\modules\admin\modules\tests\controllers
 */
class TestsController extends Controller
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
            'dataProvider' => Test::search(),
            'course' => $course,
            'questions' => TestQuestion::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('test_uuid')
                ->indexBy('test_uuid')->column(),
            'attempts' => Attempt::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('test_uuid')
                ->indexBy('test_uuid')->column(),
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

        $model = new Test([
            'course_uuid' => $course_uuid,
            'active' => true,
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
        /* @var Test $model */
        $model = Test::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Test not found.');
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
        /* @var Test $model */
        $model = Test::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Test not found.');
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
        $models = Test::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param Test $model
     * @param Test $original
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
            $this->duplicateQuestions($original, $model);
        }

        return $model->attributes;
    }

    /**
     * @param Test $original
     * @param Test $model
     */
    protected function duplicateQuestions($original, $model)
    {
        $data = [];
        $questions = TestQuestion::find()
            ->where(['test_uuid' => $original->uuid])
            ->select('question_uuid')
            ->column();

        foreach ($questions as $question) {
            $data[] = [
                'test_uuid' => $model->uuid,
                'question_uuid' => $question
            ];
        }

        \Yii::$app->db->createCommand()
            ->batchInsert(TestQuestion::tableName(), ['test_uuid', 'question_uuid'], $data)
            ->execute();
    }
}
