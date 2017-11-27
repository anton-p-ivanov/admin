<?php

namespace forms\controllers;

use app\components\behaviors\ConfirmFilter;
use app\models\User;
use forms\models\Form;
use forms\models\FormFilter;
use forms\models\FormResult;
use forms\models\FormSettings;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class FormsController
 * @package forms\controllers
 */
class FormsController extends Controller
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
            \Yii::$app->user->login(User::findOne(1));
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
        $settings = FormSettings::loadSettings();
        $dataProvider = Form::search($settings);

        if ($filter_uuid) {
            $filter = FormFilter::loadFilter($filter_uuid);
            $filter->buildQuery($dataProvider->query);
        }

        $params = [
            'dataProvider' => $dataProvider,
            'settings' => $settings,
            'isFiltered' => $filter ? $filter->isActive : false,
            'results' => FormResult::find()
                ->select(['count' => 'COUNT(*)'])
                ->groupBy('form_uuid')
                ->indexBy('form_uuid')->column()
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate()
    {
        $model = new Form([
            'active' => true,
            'active_dates' => ['active_from_date' => \Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s'))],
            'sort' => 100,
            'title' => \Yii::t('forms', 'New Web-form'),
            'code' => 'WEB_FORM_' . \Yii::$app->security->generateRandomString(6)
        ]);

        if (!$model->save()) {
            throw new HttpException(500, 'Could not create new form.');
        }

        \Yii::$app->session->setFlash('FORM_CREATED');

        return $this->renderAjax('edit', [
            'model' => $model,
            'workflow' => $model->workflow
        ]);
    }

    /**
     * @param $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var Form $model */
        $model = Form::findOne($uuid);

        if (!$model) {
            throw new HttpException(404);
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        // Format dates into human readable format
        $model->formatDatesArray(['active_from_date', 'active_to_date']);

        return $this->renderPartial('edit', [
            'model' => $model,
            'workflow' => $model->workflow
        ]);
    }

    /**
     * @param string $uuid
     * @param bool $deepCopy
     * @return array|string
     * @throws HttpException
     */
    public function actionCopy($uuid, $deepCopy = false)
    {
        /* @var Form $model */
        $model = Form::findOne($uuid);

        if (!$model) {
            throw new HttpException(404);
        }

        // Makes a form`s copy
        $copy = $model->duplicate($deepCopy);

        // Format dates into human readable format
        $copy->formatDatesArray(['active_from_date', 'active_to_date']);

        return $this->renderPartial('copy', [
            'model' => $copy,
            'workflow' => $copy->workflow
        ]);
    }

    /**
     * @return boolean
     * @todo check whether fields, statuses and their workflow records are deleted with form.
     */
    public function actionDelete()
    {
        $selected = \Yii::$app->request->post('selection', \Yii::$app->request->get('uuid'));
        $models = Form::findAll($selected);
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
        $model = FormFilter::loadFilter($filter_uuid);

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
        $model = FormSettings::loadSettings();

        if ($model->load(\Yii::$app->request->post())) {
            // Set valid response format
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $action = \Yii::$app->request->post('action');
            if ($action === self::ACTION_RESET) {
                return FormSettings::reset();
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
     * @param Form $model
     * @return array
     */
    protected function postCreate(Form $model)
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
