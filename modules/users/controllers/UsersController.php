<?php

namespace users\controllers;

use app\components\actions\FilterAction;
use app\components\actions\SettingsAction;
use app\components\behaviors\ConfirmFilter;
use app\models\Workflow;
use users\models\User;
use users\models\UserFilter;
use users\models\UserPassword;
use users\models\UserSettings;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class UsersController
 * @package users\controllers
 */
class UsersController extends Controller
{
    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'filter' => [
                'class' => FilterAction::className(),
                'modelClass' => UserFilter::className()
            ],
            'settings' => [
                'class' => SettingsAction::className(),
                'modelClass' => UserSettings::className()
            ]
        ];
    }

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
     * @return string
     */
    public function actionIndex()
    {
        $filter = null;
        $settings = UserSettings::loadSettings();
        $dataProvider = User::search($settings);

        if ($filter_uuid = \Yii::$app->request->get('filter_uuid')) {
            $filter = UserFilter::loadFilter($filter_uuid);
            $filter->buildQuery($dataProvider->query);
        }

        $params = [
            'dataProvider' => $dataProvider,
            'settings' => $settings,
            'isFiltered' => $filter ? $filter->isActive : false,
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
        $password = \Yii::$app->security->generateRandomString();
        $model = new User([
            'email' => 'new-user-' . date('Ymd-His') . '@' . \Yii::$app->request->hostName,
            'fname' => 'New',
            'lname' => 'User',
            'password_new' => $password,
            'password_new_repeat' => $password
        ]);

        if (!$model->save()) {
            throw new HttpException(500, 'Could not create new user.');
        }

        // Refresh model with new data
        $model->refresh();

        \Yii::$app->session->setFlash('FORM_CREATED');

        return $this->renderPartial('edit', [
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
        /* @var User $model */
        $model = User::findOne($uuid);

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
        /* @var User $model */
        $model = User::findOne($uuid);

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
        $models = User::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param $user_uuid
     * @return array|string
     */
    public function actionPassword($user_uuid)
    {
        $model = new UserPassword(['user_uuid' => $user_uuid]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('password', ['model' => $model]);
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
