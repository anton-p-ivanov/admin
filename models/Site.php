<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Site
 *
 * @property string $uuid
 * @property string $active
 * @property string $title
 * @property string $url
 * @property string $email
 * @property string $sort
 * @property string $code
 *
 * @package app\models
 */
class Site extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%sites}}';
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return self::find()
            ->where(['active' => true])
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->select('title')
            ->indexBy('uuid')
            ->column();
    }
}
