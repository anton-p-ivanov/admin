<?php

namespace app\components\traits;

use yii\data\ActiveDataProvider;

/**
 * Trait ActiveSearch
 *
 * @package app\components\traits
 */
trait ActiveSearch
{
    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => ['title' => SORT_ASC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes()
    {
        return (new self())->attributes();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        return self::find();
    }

    /**
     * @return self
     */
    public function duplicate()
    {
        $clone = new self();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        return $clone;
    }
}