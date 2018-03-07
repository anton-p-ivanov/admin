<?php

namespace catalogs\models;

use fields\models\Property;

/**
 * Class ElementProperty
 *
 * @property string $element_uuid
 * @property string $field_uuid
 * @property string $value
 *
 * @package catalogs\models
 */
class ElementProperty extends Property
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
        return '{{%catalogs_elements_fields}}';
    }
}
