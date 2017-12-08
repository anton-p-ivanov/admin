<?php

namespace forms\modules\fields\models;

/**
 * Class FieldValidator
 *
 * @package forms\modules\fields\models
 */
class FieldValidator extends \fields\models\FieldValidator
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%forms_fields_validators}}';
    }
}