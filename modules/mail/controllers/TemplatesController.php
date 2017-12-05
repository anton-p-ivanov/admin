<?php

namespace mail\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use app\models\Workflow;
use mail\models\TemplateFilter;
use mail\models\Template;
use mail\models\TemplateSettings;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class TemplatesController
 * @package mail\controllers
 */
class TemplatesController extends Controller
{
    /**
     * Action constants
     */
    const ACTION_RESET = 'reset';

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
     * @param string|null $filter_uuid
     * @return string
     */
    public function actionIndex($filter_uuid = null)
    {
        $filter = null;
        $settings = TemplateSettings::loadSettings();
        $dataProvider = Template::search($settings);

        if ($filter_uuid) {
            $filter = TemplateFilter::loadFilter($filter_uuid);
            $filter->buildQuery($dataProvider->query);
        }

        $params = [
            'dataProvider' => $dataProvider,
            'isFiltered' => $filter ? $filter->isActive : false,
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @return array|string
     */
    public function actionCreate()
    {
        $model = new Template([
            'code' => 'MAIL_TPL_' . \Yii::$app->security->generateRandomString(6)
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
            'workflow' => new Workflow()
        ]);
    }

    /**
     * @param $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var Template $model */
        $model = Template::findOne($uuid);

        if (!$model) {
            throw new HttpException(404);
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
            'workflow' => $model->workflow
        ]);
    }

    /**
     * @param string $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid)
    {
        /* @var Template $model */
        $model = Template::findOne($uuid);

        if (!$model) {
            throw new HttpException(404);
        }

        // Makes a copy
        $copy = $model->duplicate();

        if ($copy->load(\Yii::$app->request->post())) {
            return $this->postCreate($copy);
        }

        return $this->renderPartial('copy', [
            'model' => $copy,
            'workflow' => new Workflow()
        ]);
    }

    /**
     * @return boolean
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = Template::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param string $filter_uuid
     * @return array|string
     */
    public function actionFilter($filter_uuid = null)
    {
        $model = TemplateFilter::loadFilter($filter_uuid);

        if ($model->load(\Yii::$app->request->post())) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $action = \Yii::$app->request->post('action');
            if ($action === self::ACTION_RESET) {
                $model->delete();
                return ['url' => Url::to(['index'])];
            }

            // Validate user inputs
            $errors = ActiveForm::validate($model);

            if ($errors) {
                \Yii::$app->response->statusCode = 206;
                return $errors;
            }

            $model->save(false);

            return ['url' => Url::to(['index', 'filter_uuid' => $model->uuid])];
        }

        return $this->renderPartial('filter', ['model' => $model]);
    }

    /**
     * @return array|string
     */
    public function actionSettings()
    {
        $model = TemplateSettings::loadSettings();

        if ($model->load(\Yii::$app->request->post())) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $action = \Yii::$app->request->post('action');
            if ($action === self::ACTION_RESET) {
                return TemplateSettings::reset();
            }

            // Validate user inputs
            $errors = ActiveForm::validate($model);

            if ($errors) {
                \Yii::$app->response->statusCode = 206;
                return $errors;
            }

            $model->save(false);

            return $model->attributes;
        }

        return $this->renderAjax('settings', ['model' => $model]);
    }

    /**
     * @param Template $model
     * @return array
     */
    protected function postCreate(Template $model)
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
