<?php

namespace accounts\models;

use accounts\modules\admin\modules\fields\models\Field;
use fields\models\Property;

/**
 * Class AccountProperty
 *
 * @property string $element_uuid
 * @property string $field_uuid
 * @property string $value
 *
 * @package accounts\models
 */
class AccountProperty extends Property
{
    /**
     * @var string
     */
    public static $fieldModel = Field::class;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_properties}}';
    }
}
