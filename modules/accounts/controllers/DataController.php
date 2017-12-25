<?php

namespace accounts\controllers;

use accounts\models\Account;
use accounts\models\AccountData;
use users\models\User;
use yii\filters\AjaxFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class DataController
 * @package accounts\controllers
 */
class DataController extends Controller
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

        // set alias
        \Yii::setAlias('@fields', '@app/modules/fields');

        return $isValid;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ajax'] = [
            'class' => AjaxFilter::className(),
        ];

        return $behaviors;
    }

    /**
     * @param $account_uuid
     * @return string
     */
    public function actionIndex($account_uuid)
    {
        $model = AccountData::findOne(['account_uuid' => $account_uuid]) ?: new AccountData(['account_uuid' => $account_uuid]);

        return $this->renderPartial('index', [
            'model' => $model,
            'data' => $model->data ? Json::decode($model->data) : []
        ]);
    }

    /**
     * @param string $account_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($account_uuid)
    {
        $account = Account::findOne($account_uuid);

        if (!$account) {
            throw new HttpException(404, 'Account not found.');
        }

        $model = AccountData::findOne(['account_uuid' => $account_uuid]) ?: new AccountData(['account_uuid' => $account_uuid]);
        $model->data = Json::decode($model->data);

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postCreate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model
        ]);
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
