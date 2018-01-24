<?php

namespace training\models;

use yii\db\ActiveRecord;

/**
 * Class TestQuestion
 *
 * @property string $test_uuid
 * @property string $question_uuid
 *
 * @package training\models
 */
class TestQuestion extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%training_tests_questions}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('training/tests', $message, $params);
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }
}