<?php

namespace training\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class AttemptData
 *
 * @property string $attempt_uuid
 * @property string $question_uuid
 * @property string $answer_uuid
 * @property boolean $value
 *
 * @property Question $question
 * @property Answer $answer
 *
 * @package training\models
 */
class AttemptData extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%training_attempts_data}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::class, ['uuid' => 'question_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(Answer::class, ['uuid' => 'answer_uuid']);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['answer_uuid', 'exist', 'targetClass' => Answer::class, 'targetAttribute' => 'uuid', 'allowArray' => true],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params)
    {
        return new ActiveDataProvider([
            'pagination' => false,
            'sort' => false,
            'query' => Question::find()
                ->joinWith(['test', 'lesson'])
                ->where($params)
                ->orderBy([
                    '{{%training_lessons}}.[[sort]]' => SORT_ASC,
                    '{{%training_lessons}}.[[title]]' => SORT_ASC,
                    '{{%training_questions}}.[[sort]]' => SORT_ASC,
                    '{{%training_questions}}.[[title]]' => SORT_ASC
                ])
        ]);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            $this->value = (int) $this->answer->isValid();
        }

        return $isValid;
    }
}