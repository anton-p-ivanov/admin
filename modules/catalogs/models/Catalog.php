<?php

namespace catalogs\models;

use yii\db\ActiveRecord;

/**
 * Class Catalog
 *
 * @package catalogs\models
 */
class Catalog extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs}}';
    }
}
