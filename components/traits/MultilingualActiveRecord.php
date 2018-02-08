<?php

namespace app\components\traits;

use omgdef\multilingual\MultilingualQuery;

/**
 * Trait MultilingualActiveRecord
 *
 * @package app\components\traits
 */
trait MultilingualActiveRecord
{
    /**
     * @return MultilingualQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }
}