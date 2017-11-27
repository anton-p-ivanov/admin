<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class UserSettings
 * @property string $user_uuid
 * @property string $module
 * @property string $name
 * @property string $value
 *
 * @package app\models
 */
class UserSettings extends ActiveRecord
{
    /**
     * @var string
     */
    protected static $_moduleName;
    /**
     * @var string
     */
    protected static $_settingName;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_settings}}';
    }

    /**
     * @return UserSettings
     */
    public static function loadSettings()
    {
        $conditions = [
            'user_uuid' => \Yii::$app->user->id,
            'module' => static::$_moduleName,
            'name' => static::$_settingName
        ];

        return static::findOne($conditions) ?: new static();
    }

    /**
     * @return int
     */
    public static function reset()
    {
        $conditions = [
            'user_uuid' => \Yii::$app->user->id,
            'module' => static::$_moduleName,
            'name' => static::$_settingName
        ];

        return self::deleteAll($conditions);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        // Decode settings from JSON-string
        $values = Json::decode($this->value);
        foreach ($values as $attribute => $value) {
            if (property_exists($this, $attribute)) {
                $this->$attribute = $value;
            }
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert) && !\Yii::$app->user->isGuest;

        if ($isValid) {
            $this->user_uuid = \Yii::$app->user->id;
        }

        return $isValid;
    }
}
