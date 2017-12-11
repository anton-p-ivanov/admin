<?php

namespace users\modules\fields\models;

/**
 * Class FieldValue
 *
 * @package users\modules\fields\models
 */
class FieldValue extends \fields\models\FieldValue
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_fields_values}}';
    }
}