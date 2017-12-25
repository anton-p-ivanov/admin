<?php

namespace accounts\controllers;

use accounts\models\Account;
use accounts\models\AccountContact;
use app\components\behaviors\ConfirmFilter;
use app\models\User;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ContactsController
 * @package accounts\controllers
 */
class ContactsController extends Controller
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
        ];

        return $behaviors;
    }

    /**
     * @param string $account_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($account_uuid)
    {
        $account = Account::findOne($account_uuid);

        if (!$account) {
            throw new HttpException(404, 'Account not found.');
        }

        return $this->renderPartial('index', [
            'dataProvider' => AccountContact::search($account_uuid),
            'account' => $account
        ]);
    }

    /**
     * @param string $account_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionCreate($account_uuid)
    {
        /* @var Account $account */
        $account = Account::findOne($account_uuid);

        if (!$account) {
            throw new HttpException(404, 'Account not found.');
        }

        $model = new AccountContact([
            'account_uuid' => $account_uuid,
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($uuid)
    {
        /* @var AccountContact $model */
        $model = AccountContact::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Account contact not found.');
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
        /* @var AccountContact $model */
        $model = AccountContact::findOne($uuid);

        if (!$model) {
            throw new HttpException(404, 'Account contact not found.');
        }

        // Makes a status copy
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
        $models = AccountContact::findAll($selected);
        $counter = 0;

        foreach ($models as $model) {
            $counter += (int) $model->delete();
        }

        return $counter === count($models);
    }

    /**
     * @param AccountContact $model
     * @return array
     */
    protected function postCreate(AccountContact $model)
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
