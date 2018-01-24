<?php

namespace training\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\traits\ActiveSearch;
use yii\data\ActiveDataProvider;

/**
 * Class Answer
 *
 * @property Question $question
 *
 * @package training\modules\admin\models
*/
class Answer extends \training\models\Answer
{
    use ActiveSearch;

    /**
     * @param string $question_uuid
     * @return ActiveDataProvider
     */
    public static function search($question_uuid)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($question_uuid),
            'sort' => [
                'defaultOrder' => ['sort' => SORT_ASC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @param string $question_uuid
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($question_uuid)
    {
        return self::find()->where(['question_uuid' => $question_uuid]);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::className();

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'answer' => 'Answer',
            'valid' => 'Valid',
            'sort' => 'Sort'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'answer' => 'Answer text.',
            'valid' => 'Whether this answer is correct for selected question.',
            'sort' => 'Sorting index. Default is 100.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['answer', 'required', 'message' => self::t('{attribute} is required.')],
            ['valid', 'boolean'],
            ['valid', 'default', 'value' => 0],
            ['sort', 'integer', 'min' => 0, 'message' => self::t('{attribute} value must be greater than {min, number}.')],
            ['sort', 'default', 'value' => 100],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            if ($this->valid && $this->question->type === Question::TYPE_SINGLE) {
                self::updateAll(['valid' => 0], ['question_uuid' => $this->question_uuid]);
            }
        }

        return $isValid;
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

        $clone->question_uuid = $this->question_uuid;

        return $clone;
    }
}