<?php

namespace users\controllers;

use app\models\User;
use users\models\Field;
use users\models\UserProperty;
use yii\filters\AjaxFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class PropertiesController
 *
 * @package users\controllers
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
     * @param string $user_uuid
     * @return string
     * @throws HttpException
     */
    public function actionIndex($user_uuid)
    {
        $user = User::findOne($user_uuid);

        if (!$user) {
            throw new HttpException(404, 'User not found.');
        }

        $params = [
            'dataProvider' => Field::search(),
            'user' => $user,
            'properties' => UserProperty::find()
                ->where(['user_uuid' => $user_uuid])
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
     * @param string $user_uuid
     * @param string $field_uuid
     * @return array|string
     * @throws HttpException
     */
    public function actionEdit($user_uuid, $field_uuid)
    {
        $user = User::findOne($user_uuid);
        if (!$user) {
            throw new HttpException(404, 'Invalid user identifier.');
        }

        $field = Field::findOne(['uuid' => $field_uuid]);
        if (!$field) {
            throw new HttpException(404, 'Invalid field identifier.');
        }

        $params = [
            'user_uuid' => $user_uuid,
            'field_uuid' => $field_uuid,
        ];

        /* @var UserProperty $model */
        $model = UserProperty::findOne($params) ?: new UserProperty($params);

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
     * @param UserProperty $model
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
