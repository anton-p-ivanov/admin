<?php

namespace catalogs\modules\admin\modules\fields\models;

/**
 * Class FieldValue
 *
 * @property Field $field
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Field::className(), ['uuid' => 'field_uuid']);
    }
}