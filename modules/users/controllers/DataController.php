<?php

namespace users\controllers;

use users\models\UserData;
use users\models\User;
use yii\filters\AjaxFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class DataController
 * @package users\controllers
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
     * @param $user_uuid
     * @return string
     */
    public function actionIndex($user_uuid)
    {
        $model = UserData::findOne(['user_uuid' => $user_uuid]) ?: new UserData(['user_uuid' => $user_uuid]);

        return $this->renderPartial('index', [
            'model' => $model,
            'data' => $model->data ? Json::decode($model->data) : []
        ]);
    }

    /**
     * @param string $user_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($user_uuid)
    {
        $user = User::findOne($user_uuid);

        if (!$user) {
            throw new HttpException(404, 'User not found.');
        }

        $model = UserData::findOne(['user_uuid' => $user_uuid]) ?: new UserData(['user_uuid' => $user_uuid]);
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
