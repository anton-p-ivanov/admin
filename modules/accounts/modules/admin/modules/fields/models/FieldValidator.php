<?php

namespace accounts\modules\admin\modules\fields\models;

/**
 * Class FieldValidator
 *
 * @property Field $field
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Field::className(), ['uuid' => 'field_uuid']);
    }
}