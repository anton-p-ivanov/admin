<?php

namespace users\modules\admin\modules\fields\models;

/**
 * Class FieldValue
 *
 * @package users\modules\admin\modules\fields\models
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