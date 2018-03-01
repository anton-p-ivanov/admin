<?php

namespace training\models;

use app\models\Workflow;
use yii\db\ActiveRecord;

/**
 * Class Course
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 * @property string $description_format
 * @property bool $active
 * @property int $sort
 * @property string $code
 * @property string $tree_uuid
 * @property string $workflow_uuid
 *
 * @property Workflow $workflow
 *
 * @package training\models
 */
class Course extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%training_courses}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('training/courses', $message, $params);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return self::find()
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
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessons()
    {
        return $this->hasMany(Lesson::class, ['course_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTests()
    {
        return $this->hasMany(Test::class, ['course_uuid' => 'uuid']);
    }
}