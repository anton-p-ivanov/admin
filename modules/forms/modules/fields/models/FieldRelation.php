<?php

namespace forms\modules\fields\models;

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
 * @package forms\modules\fields\models
 */
class FieldRelation extends ActiveRecord
{
    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('fields', $message, $params);
    }

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
        $behaviors[] = PrimaryKeyBehavior::className();

        return $behaviors;
    }

    /**
     * @param string $field_uuid
     * @return ActiveDataProvider
     */
    public static function search($field_uuid)
    {
        return new ActiveDataProvider([
            'query' => static::prepareSearchQuery($field_uuid),
            'sort' => false,
            'pagination' => false
        ]);
    }

    /**
     * @param string $field_uuid
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($field_uuid)
    {
        return static::find()->where(['field_uuid' => $field_uuid])->orderBy('sort');
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