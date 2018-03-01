<?php

namespace users\models;

use users\validators\PropertiesValidator;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class UserData
 *
 * @property string $user_uuid
 * @property string $field_uuid
 * @property string $value
 *
 * @property Field $field
 *
 * @package users\models
 */
class UserProperty extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_properties}}';
    }

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
        return [
            'field.label' => [
                'asc' => ['{{%users_fields}}.[[label]]' => SORT_ASC],
                'desc' => ['{{%users_fields}}.[[label]]' => SORT_DESC],
            ],
            'field.sort' => [
                'asc' => ['{{%users_fields}}.[[sort]]' => SORT_ASC],
                'desc' => ['{{%users_fields}}.[[sort]]' => SORT_DESC]
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
        return $this->hasOne(Field::class, ['uuid' => 'field_uuid']);
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