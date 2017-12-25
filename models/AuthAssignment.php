<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class AuthAssignment
 *
 * @property string $uuid
 * @property string $item_name
 * @property int $user_id
 * @property string $created_at
 * @property string $valid_from_date
 * @property string $valid_to_date
 *
 * @package app\models
 */
class AuthAssignment extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%auth_assignments}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                'item_name',
                'exist',
                'targetClass' => AuthItem::className(),
                'targetAttribute' => 'name',
                'message' => \Yii::t('app', 'Invalid role.')
            ],
            [
                'user_id',
                'exist',
                'targetClass' => User::className(),
                'targetAttribute' => 'uuid',
                'message' => \Yii::t('app', 'Invalid user.')
            ],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($isValid = parent::beforeSave($insert)) {
            $this->created_at = new Expression('NOW()');
        }

        return $isValid;
    }
}
