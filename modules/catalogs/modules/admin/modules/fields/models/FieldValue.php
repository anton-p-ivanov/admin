<?php

namespace catalogs\modules\admin\modules\fields\models;

/**
 * Class FieldValue
 *
 * @package catalogs\modules\admin\modules\fields\models
 */
class FieldValue extends \fields\models\FieldValue
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_fields_values}}';
    }
}