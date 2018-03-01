<?php

namespace training\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\traits\ActiveSearch;
use yii\data\ActiveDataProvider;

/**
 * Class Question
 *
 * @property Answer[] $answers
 * @method Question duplicate()
 *
 * @package training\modules\admin\models
 */
class Question extends \training\models\Question
{
    use ActiveSearch;

    /**
     * @param string $lesson_uuid
     * @return ActiveDataProvider
     */
    public static function search($lesson_uuid)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($lesson_uuid),
            'sort' => [
                'defaultOrder' => ['title' => SORT_ASC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @param string $lesson_uuid
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($lesson_uuid)
    {
        return self::find()->where(['lesson_uuid' => $lesson_uuid]);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::class;

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'active' => 'Active',
            'lesson_uuid' => 'Lesson',
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'Type',
            'value' => 'Score',
            'sort' => 'Sort',
            'answers' => 'Answers'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'active' => 'Whether question is active.',
            'lesson_uuid' => 'Choose one of available lessons.',
            'title' => 'Up to 500 characters length.',
            'description' => 'Short question description.',
            'type' => 'Choose one of available types.',
            'value' => 'Score counted on correct answer.',
            'sort' => 'Sorting index. Default is 100.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['lesson_uuid', 'title'], 'required', 'message' => self::t('{attribute} is required.')],
            ['lesson_uuid', 'exist', 'targetClass' => Lesson::class, 'targetAttribute' => 'uuid'],
            ['title', 'string', 'max' => 500, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['description', 'safe'],
            ['description', 'default', 'value' => ''],
            ['active', 'boolean'],
            ['active', 'default', 'value' => 1],
            ['type', 'in', 'range' => array_keys(self::getTypes())],
            ['type', 'default', 'value' => self::TYPE_DEFAULT],
            ['sort', 'integer', 'min' => 100, 'message' => self::t('{attribute} value must be greater than {min, number}.')],
            ['sort', 'default', 'value' => 100],
            ['value', 'integer', 'min' => 0, 'message' => self::t('{attribute} value must be greater than {min, number}.')],
            ['value', 'default', 'value' => 10]
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->type === Question::TYPE_TEXT) {
            Answer::deleteAll(['question_uuid' => $this->uuid]);
        }
        else if ($this->type === Question::TYPE_SINGLE) {
            $valid = Answer::findOne(['question_uuid' => $this->uuid, 'valid' => 1]);
            if ($valid) {
                Answer::updateAll(
                    ['valid' => 0],
                    '[[valid]] = :valid AND [[question_uuid]] = :question_uuid AND [[uuid]] != :uuid',
                    [':uuid' => $valid->uuid, ':valid' => 1, ':question_uuid' => $this->uuid]
                );
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['question_uuid' => 'uuid']);
    }

    /**
     * @return bool
     */
    public function hasValidAnswer()
    {
        $filtered = array_filter($this->answers, function (Answer $answer) {
            return (int) $answer->valid === 1;
        });

        return count($filtered) > 0;
    }
}