<?php

namespace fields\models;

use fields\validators\PropertiesValidator;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class Property
 *
 * @property string $value
 * @property Field $field
 *
 * @package fields\models
 */
class Property extends ActiveRecord
{
    /**
     * @var \yii\db\ActiveRecord
     */
    public static $fieldModel;

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params = [])
    {
        $defaultOrder = ['field.sort' => SORT_ASC, 'field.label' => SORT_ASC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($params),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => $defaultOrder,
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes()
    {
        $tableName = (static::$fieldModel)::tableName();

        return [
            'field.label' => [
                'asc' => [$tableName . '.[[label]]' => SORT_ASC],
                'desc' => [$tableName . '.[[label]]' => SORT_DESC],
            ],
            'field.sort' => [
                'asc' => [$tableName . '.[[sort]]' => SORT_ASC],
                'desc' => [$tableName . '.[[sort]]' => SORT_DESC]
            ]
        ];
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params = [])
    {
        return self::find()
            ->joinWith('field')
            ->where($params);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(static::$fieldModel, ['uuid' => 'field_uuid']);
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $isValid = parent::beforeValidate();

        if ($isValid) {
            if ($this->field->isMultiple()) {
                $this->value = Json::encode($this->value);
            }
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['value', 'safe'],
            ['value', PropertiesValidator::class]
        ];
    }
}