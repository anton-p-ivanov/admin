<?php

namespace accounts\modules\admin\modules\fields\models;

use yii\data\ActiveDataProvider;
use yii\validators\UniqueValidator;

/**
 * Class Field
 *
 * @package accounts\modules\admin\modules\fields\models
 */
class Field extends \fields\models\Field
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_fields}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValidators()
    {
        return $this->hasMany(FieldValidator::class, ['field_uuid' => 'uuid'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValues()
    {
        return $this->hasMany(FieldValue::class, ['field_uuid' => 'uuid'])->orderBy(['sort' => SORT_ASC]);
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
            UniqueValidator::class,
            'message' => self::t('Field with code `{value}` is already exists.')
        ];

        return $rules;
    }
}