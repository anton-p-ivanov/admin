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
        return \Yii::t('accounts/addresses', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'address.type.title' => 'Type',
            'address' => 'Address'
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
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($params),
            'sort' => false
        ]);
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params)
    {
        return self::find()->joinWith('address.type')
            ->where($params)
            ->orderBy(['{{%addresses_types}}.[[sort]]' => SORT_ASC]);
    }
}