<?php

namespace accounts\models;

use app\models\Address;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class AccountAddress
 *
 * @property string $account_uuid
 * @property string $address_uuid
 *
 * @property Address $address
 *
 * @package account\models
 */
class AccountAddress extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_addresses}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('addresses', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'address.type.title' => 'Type'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::class, ['uuid' => 'address_uuid']);
    }

    /**
     * @param string $account_uuid
     * @return ActiveDataProvider
     */
    public static function search($account_uuid)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($account_uuid),
            'sort' => false
        ]);
    }

    /**
     * @param string $account_uuid
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($account_uuid)
    {
        return self::find()->joinWith('address.type')
            ->where(['account_uuid' => $account_uuid])
            ->orderBy(['{{%addresses_types}}.[[sort]]' => SORT_ASC]);
    }
}