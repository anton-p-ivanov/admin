<?php

namespace catalogs\modules\admin\modules\fields\models;

/**
 * Class FieldValidator
 *
 * @package catalogs\modules\admin\modules\fields\models
 */
class FieldValidator extends \fields\models\FieldValidator
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_fields_validators}}';
    }
}