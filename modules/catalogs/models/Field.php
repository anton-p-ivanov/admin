<?php

namespace catalogs\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class Field
 *
 * @property string $uuid
 * @property bool $multiple
 * @property string $catalog_uuid
 *
 * @package catalogs\models
 */
class Field extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_fields}}';
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params = [])
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($params),
            'pagination' => false,
            'sort' => false
        ]);
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params = [])
    {
        return self::find()
            ->orderBy(['sort' => SORT_ASC, 'label' => SORT_ASC])
            ->where($params);
    }

    /**
     * @return int
     */
    public function isMultiple()
    {
        return (int) $this->multiple === 1;
    }
}
