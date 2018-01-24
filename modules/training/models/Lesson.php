<?php

namespace training\models;

use app\models\Workflow;
use yii\db\ActiveRecord;

/**
 * Class Lesson
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 * @property string $description_format
 * @property bool $active
 * @property int $sort
 * @property string $code
 * @property string $course_uuid
 * @property string $workflow_uuid
 *
 * @property Course $course
 * @property Workflow $workflow
 *
 * @package training\models
 */
class Lesson extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%training_lessons}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('training/lessons', $message, $params);
    }

    /**
     * @param string $course_uuid
     * @return array
     */
    public static function getList($course_uuid)
    {
        return self::find()
            ->where(['course_uuid' => $course_uuid])
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->select('title')
            ->indexBy('uuid')
            ->column();
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
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::className(), ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['uuid' => 'course_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['lesson_uuid' => 'uuid']);
    }
}