<?php

namespace forms\modules\fields\models;

/**
 * Class FieldValue
 *
 * @package forms\modules\fields\models
 */
class FieldValue extends \fields\models\FieldValue
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%forms_fields_values}}';
    }
}