<?php

namespace users\controllers;

use app\models\User;
use users\models\Field;
use users\models\UserProperty;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Class PropertiesController
 *
 * @package users\controllers
 */
class PropertiesController extends \fields\controllers\PropertiesController
{
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
}
