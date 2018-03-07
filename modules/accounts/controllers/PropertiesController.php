<?php

namespace accounts\controllers;

use accounts\models\Account;
use accounts\models\AccountProperty;
use accounts\models\Field;
use yii\helpers\Json;
use yii\web\HttpException;

/**
 * Class PropertiesController
 *
 * @package accounts\controllers
 */
class PropertiesController extends \fields\controllers\PropertiesController
{
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
}
