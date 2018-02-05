<?php

namespace forms\modules\admin\modules\fields\models;

/**
 * Class FieldValue
 *
 * @package forms\modules\admin\modules\fields\models
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