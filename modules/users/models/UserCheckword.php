<?php
namespace users\models;

use yii\db\ActiveRecord;

/**
 * Class UserCheckword
 *
 * @property string $user_uuid
 * @property string $checkword
 *
 * @package users\models
 */
class UserCheckword extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_checkwords}}';
    }
}
