<?php

namespace training\models;

use yii\db\ActiveRecord;

/**
 * Class Attempt
 *
 * @property string $uuid
 * @property string $test_uuid
 * @property string $user_uuid
 * @property bool $success
 * @property \DateTime $begin_date
 * @property \DateTime $end_date
 *
 * @package training\models
 */
class Attempt extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%training_attempts}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('training/attempts', $message, $params);
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }
}