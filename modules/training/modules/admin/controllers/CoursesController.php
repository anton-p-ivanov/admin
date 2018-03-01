<?php

namespace training\modules\admin\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use app\models\WorkflowStatus;
use training\modules\admin\components\traits\Duplicator;
use training\modules\admin\models\Course;
use training\modules\admin\models\Lesson;
use training\modules\admin\models\Test;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class CoursesController
 *
 * @package training\modules\admin\controllers
 */
class CoursesController extends Controller
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
     * @return string
     */
    public function actionIndex()
    {
        $params = [
            'dataProvider' => Course::search(),
            'lessons' => Lesson::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('course_uuid')
                ->indexBy('course_uuid')->column(),
            'tests' => Test::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('course_uuid')
                ->indexBy('course_uuid')->column()
        ];

        return $this->render('index', $params);
    }

    /**
     * @return array|string
     */
    public function actionCreate()
    {
        $model = new Course([
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
        /* @var Course $model */
        $model = Course::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Training course not found.');
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
        /* @var Course $model */
        $model = Course::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Training course not found.');
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
        $models = Course::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param Course $model
     * @param Course $original
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
            foreach ($original->lessons as $lesson) {
                $this->duplicateLesson($lesson, $model->uuid);
            }

            foreach ($original->tests as $test) {
                $this->duplicateTest($test, $model->uuid);
            }
        }

        return $model->attributes;
    }
}
