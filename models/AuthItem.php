<?php

namespace app\models;

use i18n\components\ActiveRecord;
use i18n\components\MultilingualBehavior;

/**
 * Class AuthItem
 * @property string $name
 * @property int $type
 * @property string $description
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 *
 * @package app\models
 */
class AuthItem extends ActiveRecord
{
    /**
     * Auth items types
     */
    const
        AUTH_ITEM_ROLE = 1,
        AUTH_ITEM_PERMISSION = 2;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%auth_items}}';
    }

    /**
     * @return AuthItemQuery
     */
    public static function find()
    {
        return new AuthItemQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ml'] = [
            'class' => MultilingualBehavior::class,
            'langForeignKey' => 'item_name',
            'tableName' => '{{%auth_items_i18n}}',
            'attributes' => ['description']
        ];

        return $behaviors;
    }
    /**
     * @return array
     */
    public static function getRoles()
    {
        return self::find()
            ->joinWith('translation')
            ->select('description')
            ->indexBy('name')
            ->roles()
            ->column();
    }
}
