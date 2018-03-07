<?php
namespace catalogs\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

/**
 * Class ElementTreeQuery
 *
 * @method ElementTreeQuery roots()
 * @method ElementTreeQuery children($depth)
 *
 * @package catalogs\models
 */
class ElementTreeQuery extends ActiveQuery
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::class,
        ];
    }

    /**
     * @param \yii\db\Connection $db
     * @return ElementTree|\yii\db\ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param string $type
     * @return ElementTreeQuery
     */
    public function type($type)
    {
        return $this->joinWith('element')->andWhere(['type' => $type]);
    }
}