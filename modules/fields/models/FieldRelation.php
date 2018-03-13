<?php

namespace fields\models;

use app\components\behaviors\PrimaryKeyBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class FieldRelation
 *
 * @property string $uuid
 * @property string $field_uuid
 * @property string $sort
 *
 * @package fields\models
 */
class FieldRelation extends ActiveRecord
{
    /**
     * @return array
     */
    public function transactions()
    {
        return [static::SCENARIO_DEFAULT => static::OP_ALL];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::class;

        return $behaviors;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params = [])
    {
        return new ActiveDataProvider([
            'query' => static::prepareSearchQuery($params),
            'sort' => false,
            'pagination' => false
        ]);
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params)
    {
        return static::find()->where($params)->orderBy('sort');
    }

    /**
     * @return FieldRelation
     */
    public function duplicate()
    {
        $clone = new static([
            'field_uuid' => $this->field_uuid,
            'sort' => 100,
        ]);

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        return $clone;
    }
}