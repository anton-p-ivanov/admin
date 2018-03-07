<?php

namespace catalogs\modules\admin\modules\fields\models;

/**
 * Class FieldValidator
 *
 * @property Field $field
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Field::class, ['uuid' => 'field_uuid']);
    }
}