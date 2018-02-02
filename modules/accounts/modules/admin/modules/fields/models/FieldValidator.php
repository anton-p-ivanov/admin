<?php

namespace accounts\modules\admin\modules\fields\models;

/**
 * Class FieldValidator
 *
 * @package accounts\modules\admin\modules\fields\models
 */
class FieldValidator extends \fields\models\FieldValidator
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_fields_validators}}';
    }
}