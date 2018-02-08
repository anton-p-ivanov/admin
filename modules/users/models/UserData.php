<?php

namespace users\models;

use yii\db\ActiveRecord;

/**
 * Class UserData
 *
 * @property string $user_uuid
 * @property string $field_uuid
 * @property string $value
 *
 * @package users\models
 */
class UserData extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_data}}';
    }
}