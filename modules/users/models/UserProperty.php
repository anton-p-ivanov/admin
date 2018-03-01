<?php

namespace users\models;

use fields\models\Property;
use users\modules\admin\modules\fields\models\Field;

/**
 * Class UserProperty
 *
 * @property string $user_uuid
 * @property string $field_uuid
 * @property string $value
 *
 * @package users\models
 */
class UserProperty extends Property
{
    /**
     * @var \yii\db\ActiveRecord
     */
    public static $fieldModel = Field::class;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_properties}}';
    }
}