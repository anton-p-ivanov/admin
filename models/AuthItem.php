<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class AuthItem
 * @property string $name
 * @property int $type
 * @property string $description
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * @package app\models
 */
class AuthItem extends ActiveRecord
{
    /**
     * Auth items types
     */
    const
        AUTH_ITEM_ROLE = 1,
        AUTH_ITEM_PERMISSION = 2;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%auth_items}}';
    }

    /**
     * @return AuthItemQuery
     */
    public static function find()
    {
        return new AuthItemQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getRoles()
    {
        return self::find()->roles()->select('description')->indexBy('name')->column();
    }
}
