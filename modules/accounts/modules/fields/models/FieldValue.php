<?php

namespace accounts\modules\fields\models;

/**
 * Class FieldValue
 *
 * @package accounts\modules\fields\models
 */
class FieldValue extends \fields\models\FieldValue
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_fields_values}}';
    }
}