<?php

namespace users\modules\admin\modules\fields\models;

use yii\data\ActiveDataProvider;
use yii\validators\UniqueValidator;

/**
 * Class Field
 *
 * @package users\modules\admin\modules\fields\models
 */
class Field extends \fields\models\Field
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_fields}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValidators()
    {
        return $this->hasMany(FieldValidator::className(), ['field_uuid' => 'uuid'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValues()
    {
        return $this->hasMany(FieldValue::className(), ['field_uuid' => 'uuid'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @param array $settings
     * @return ActiveDataProvider
     */
    public static function search($settings = [])
    {
        $defaultOrder = ['label' => SORT_ASC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery([]),
            'sort' => [
                'defaultOrder' => $defaultOrder,
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules[] = [
            'code',
            UniqueValidator::className(),
            'message' => self::t('Field with code `{value}` is already exists.')
        ];

        return $rules;
    }

    /**
     * @return Field[]
     */
    public static function getList()
    {
        return Field::find()
            ->where(['active' => true])
            ->orderBy(['sort' => SORT_ASC])
            ->indexBy('uuid')
            ->all();
    }
}