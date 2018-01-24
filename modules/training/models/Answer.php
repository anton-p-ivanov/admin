<?php

namespace training\models;

use yii\db\ActiveRecord;

/**
 * Class Answer
 *
 * @property string $uuid
 * @property string $question_uuid
 * @property string $answer
 * @property bool $valid
 * @property int $sort
 *
 * @property Question $question
 *
 * @package training\models
 */
class Answer extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%training_answers}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('training/answers', $message, $params);
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['uuid' => 'question_uuid']);
    }
}