<?php
namespace storage\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

/**
 * Class StorageTreeQuery
 *
 * @method StorageTreeQuery roots()
 * @method StorageTreeQuery children($depth)
 *
 * @package storage\models
 */
class StorageTreeQuery extends ActiveQuery
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

    /**
     * @param \yii\db\Connection $db
     * @return StorageTree|\yii\db\ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param string $type
     * @return StorageTreeQuery
     */
    public function type($type)
    {
        return $this->joinWith('storage')->andWhere(['type' => $type]);
    }
}