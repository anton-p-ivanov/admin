<?php

namespace training\models;

use app\models\Workflow;
use yii\db\ActiveRecord;

/**
 * Class Test
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 * @property string $description_format
 * @property bool $active
 * @property boolean $questions_random
 * @property boolean $answers_random
 * @property integer $limit_attempts
 * @property integer $limit_time
 * @property integer $limit_percent
 * @property integer $limit_value
 * @property integer $limit_questions
 * @property string $course_uuid
 * @property string $workflow_uuid
 *
 * @property Course $course
 * @property Workflow $workflow
 *
 * @package training\models
 */
class Test extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%training_tests}}';
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['uuid' => 'course_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['uuid' => 'question_uuid'])
            ->viaTable(TestQuestion::tableName(), ['test_uuid' => 'uuid']);
    }
}