<?php

namespace accounts\modules\admin\modules\fields\models;

/**
 * Class FieldValue
 *
 * @package accounts\modules\admin\modules\fields\models
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