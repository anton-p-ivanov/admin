<?php

namespace training\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\components\traits\ActiveSearch;
use app\models\WorkflowStatus;
use yii\helpers\ArrayHelper;

/**
 * Class Test
 *
 * @package training\modules\admin\models
 */
class Test extends \training\models\Test
{
    use ActiveSearch;

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::class;
        $behaviors['wf'] = WorkflowBehavior::class;

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'course_uuid' => 'Course',
            'active' => 'Active',
            'title' => 'Title',
            'description' => 'Description',
            'questions_random' => 'Random questions',
            'answers_random' => 'Random answers',
            'limit_attempts' => 'Attempts',
            'limit_time' => 'Time',
            'limit_percent' => 'Percent',
            'limit_value' => 'Score',
            'limit_questions' => 'Questions',
            'questions' => 'Questions',
            'attempts' => 'Attempts',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'course_uuid' => 'Choose one of available courses.',
            'active' => 'Whether test is active.',
            'title' => 'Up to 255 characters length.',
            'description' => 'Short test description.',
            'questions_random' => 'Display questions in random order.',
            'answers_random' => 'Display answers in random order.',
            'limit_attempts' => 'Maximum attempts counts per user.',
            'limit_time' => 'Maximum time per user.',
            'limit_percent' => 'Minimum percent to complete test.',
            'limit_value' => 'Minimum score to complete test.',
            'limit_questions' => 'Minimum questions with correct answers.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['title', 'required', 'message' => self::t('{attribute} is required.')],
            ['title', 'string', 'max' => 255, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['description', 'safe'],
            [['questions_random', 'answers_random', 'active'], 'boolean'],
            ['questions_random', 'default', 'value' => 0],
            ['answers_random', 'default', 'value' => 0],
            ['limit_attempts', 'integer', 'min' => 0, 'tooSmall' => self::t('Minimum {min, number} attempts allowed.')],
            ['limit_attempts', 'default', 'value' => 0],
            [
                'limit_time',
                'integer',
                'min' => 0,
                'max' => 240,
                'message' => self::t('Time value must be between {min, number} and {max, number} minutes.')
            ],
            ['limit_time', 'default', 'value' => 0],
            [
                'limit_percent',
                'integer',
                'min' => 0,
                'max' => 100,
                'message' => self::t('Percent value must be between {min, number} and {max, number}.')
            ],
            ['limit_percent', 'default', 'value' => 100],
            [
                'limit_value',
                'integer',
                'min' => 0,
                'message' => self::t('Score value must be greater or equal {min, number}.')
            ],
            ['limit_value', 'default', 'value' => 0],
            [
                'limit_questions',
                'integer',
                'min' => 0,
                'message' => self::t('Questions count must be greater or equal {min, number}.')
            ],
            ['limit_questions', 'default', 'value' => 0],
        ];
    }

    /**
     * @return self
     */
    public function duplicate()
    {
        $clone = new self();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        $clone->course_uuid = $this->course_uuid;

        return $clone;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->assignQuestions();
        }
    }

    /**
     * @return int
     */
    protected function assignQuestions()
    {
        $questions = [];
        $conditions = [
            'course_uuid' => $this->course_uuid,
            'active' => true,
            '{{%workflow}}.[[status]]' => WorkflowStatus::WORKFLOW_STATUS_PUBLISHED
        ];

        /* @var Lesson[] $lessons */
        $lessons = Lesson::find()->joinWith('workflow')->where($conditions)->all();

        foreach ($lessons as $lesson) {
            $conditions = ['lesson_uuid' => $lesson->uuid, 'active' => true];
            $uuid = Question::find()->where($conditions)->select('uuid')->column();
            $questions = ArrayHelper::merge($questions, $uuid);
        }

        $questions = array_map([$this, 'prepareQuestions'], $questions);

        return \Yii::$app->db->createCommand()
            ->batchInsert(TestQuestion::tableName(), ['test_uuid', 'question_uuid'], $questions)
            ->execute();
    }

    /**
     * @param string $question_uuid
     * @return array
     */
    protected function prepareQuestions($question_uuid)
    {
        return ['test_uuid' => $this->uuid, 'question_uuid' => $question_uuid];
    }
}