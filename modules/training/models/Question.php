<?php

namespace training\models;

use yii\db\ActiveRecord;

/**
 * Class Question
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 * @property string $description_format
 * @property bool $active
 * @property string $type
 * @property int $sort
 * @property string $value
 * @property string $lesson_uuid
 *
 * @property Lesson $lesson
 *
 * @package training\models
 */
class Question extends ActiveRecord
{
    /**
     * Constants
     */
    const TYPE_DEFAULT = 'S';
    const TYPE_SINGLE = 'S';
    const TYPE_MULTIPLE = 'M';
    const TYPE_TEXT = 'T';

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%training_questions}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('training/questions', $message, $params);
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        $types = [
            self::TYPE_SINGLE => 'Single answer',
            self::TYPE_MULTIPLE => 'Multiple answers',
            self::TYPE_TEXT => 'Text',
        ];

        return array_map('self::t', $types);
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
    public function getLesson()
    {
        return $this->hasOne(Lesson::class, ['uuid' => 'lesson_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['question_uuid' => 'uuid']);
    }
}