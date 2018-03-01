<?php

namespace accounts\modules\admin\modules\fields\models;

/**
 * Class FieldValue
 *
 * @property Field $field
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Field::class, ['uuid' => 'field_uuid']);
    }
}