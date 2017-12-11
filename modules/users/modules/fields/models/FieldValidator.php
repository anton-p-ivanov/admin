<?php

namespace users\modules\fields\models;

/**
 * Class FieldValidator
 *
 * @package users\modules\fields\models
 */
class FieldValidator extends \fields\models\FieldValidator
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_fields_validators}}';
    }
}