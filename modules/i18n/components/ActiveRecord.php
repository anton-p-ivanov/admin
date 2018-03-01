<?php

namespace i18n\components;

use omgdef\multilingual\MultilingualQuery;

/**
 * Class ActiveRecord
 *
 * @property ActiveRecord[] $i18n
 *
 * @package i18n\components
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @return MultilingualQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ml'] = [
            'class' => MultilingualBehavior::class,
        ];

        return $behaviors;
    }
}