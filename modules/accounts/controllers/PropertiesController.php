<?php

namespace accounts\controllers;

use accounts\models\Account;
use accounts\models\AccountProperty;
use accounts\models\Field;
use app\models\User;
use yii\filters\AjaxFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class PropertiesController
 *
 * @package accounts\controllers
 */
class PropertiesController extends Controller
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
        $behaviors['ajax'] = [
            'class' => AjaxFilter::class,
            'except' => ['index']
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

        $params = [
            'dataProvider' => Field::search(),
            'account' => $account,
            'properties' => AccountProperty::find()
                ->where(['account_uuid' => $account_uuid])
                ->joinWith('field')
                ->indexBy('field_uuid')
                ->all(),
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $params);
        }

        return $this->render('index', $params);
    }

    /**
     * @param string $account_uuid
     * @param string $field_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($account_uuid, $field_uuid)
    {
        $account = Account::findOne($account_uuid);
        if (!$account) {
            throw new HttpException(404, 'Invalid account identifier.');
        }

        $field = Field::findOne(['uuid' => $field_uuid]);
        if (!$field) {
            throw new HttpException(404, 'Invalid field identifier.');
        }

        $params = [
            'account_uuid' => $account_uuid,
            'field_uuid' => $field_uuid,
        ];

        /* @var AccountProperty $model */
        $model = AccountProperty::findOne($params) ?: new AccountProperty($params);

        if ($field->isMultiple()) {
            $model->value = Json::decode($model->value);
        }

        if ($model->load(\Yii::$app->request->post())) {
            return $this->postUpdate($model);
        }

        return $this->renderPartial('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @param AccountProperty $model
     * @return array
     */
    protected function postUpdate($model)
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
