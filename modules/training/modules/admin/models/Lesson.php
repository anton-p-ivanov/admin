<?php

namespace training\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\components\traits\ActiveSearch;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;

/**
 * Class Lesson
 *
 * @property Question[] $questions
 *
 * @package training\modules\admin\models
 */
class Lesson extends \training\models\Lesson
{
    use ActiveSearch;

    /**
     * @param string $course_uuid
     * @return ActiveDataProvider
     */
    public static function search($course_uuid)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($course_uuid),
            'sort' => [
                'defaultOrder' => ['title' => SORT_ASC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @param string $course_uuid
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($course_uuid)
    {
        return self::find()->where(['course_uuid' => $course_uuid]);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::class;
        $behaviors['wf'] = WorkflowBehavior::class;
        $behaviors['sl'] = [
            'class' => SluggableBehavior::class,
            'attribute' => 'title',
            'slugAttribute' => 'code',
            'ensureUnique' => true
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'active' => 'Active',
            'course_uuid' => 'Course',
            'title' => 'Title',
            'description' => 'Description',
            'code' => 'Code',
            'sort' => 'Sort',
            'questions' => 'Questions'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'active' => 'Whether lesson is active.',
            'course_uuid' => 'Choose one of available courses.',
            'title' => 'Up to 250 characters length.',
            'description' => 'Short lesson description.',
            'code' => 'Unique symbolic code.',
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
            [['course_uuid', 'title'], 'required', 'message' => self::t('{attribute} is required.')],
            ['course_uuid', 'exist', 'targetClass' => Course::class, 'targetAttribute' => 'uuid'],
            ['sort', 'integer', 'min' => 0, 'message' => self::t('{attribute} value must be greater than {min, number}.')],
            ['sort', 'default', 'value' => 100],
            [['title', 'code'], 'string', 'max' => 255, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['code', 'unique', 'message' => self::t('{attribute} already in use.')],
            ['code', 'match', 'pattern' => '/[\w\d\-_]+/i'],
            ['active', 'boolean'],
            ['active', 'default', 'value' => 1],
            ['description', 'safe'],
            ['description', 'default', 'value' => ''],
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
            $this->code = mb_strtoupper($this->code);
        }

        return $isValid;
    }

    /**
     * @return Lesson|bool
     */
    public function duplicate()
    {
        $clone = new self();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        $clone->code = null;

        return $clone;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['lesson_uuid' => 'uuid']);
    }
}