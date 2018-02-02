<?php

namespace users\models;

use fields\models\Field;
use users\validators\PropertiesValidator;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class UserData
 *
 * @property string $user_uuid
 * @property string $data
 *
 * @package users\models
 */
class UserData extends ActiveRecord
{
    /**
     * @var Field[]
     */
    private $_fields;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_data}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['data', 'safe'],
            ['data', PropertiesValidator::className()]
        ];
    }

    /**
     * @return array
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('users', $message, $params);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($isValid = parent::beforeSave($insert)) {
            if (is_array($this->data)) {
                $this->data = Json::encode($this->data);
            }
        }

        return $isValid;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        if ($this->_fields === null) {
            $this->_fields = Field::find()
                ->orderBy(['sort' => SORT_ASC])
                ->indexBy('code')
                ->all();
        }

        return $this->_fields;
    }
}