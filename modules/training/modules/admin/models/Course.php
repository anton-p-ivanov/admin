<?php

namespace training\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\components\traits\ActiveSearch;
use app\models\Workflow;
use yii\behaviors\SluggableBehavior;

/**
 * Class Course
 *
 * @property Lesson[] $lessons
 * @property Test[] $tests
 *
 * @package training\modules\admin\models
 */
class Course extends \training\models\Course
{
    use ActiveSearch;
    /**
     * @var array
     */
    private $_delete = [];

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::className();
        $behaviors['wf'] = WorkflowBehavior::className();
        $behaviors['sl'] = [
            'class' => SluggableBehavior::className(),
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
            'title' => 'Title',
            'description' => 'Description',
            'code' => 'Code',
            'sort' => 'Sort',
            'lessons' => 'Lessons',
            'tests' => 'Tests'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'title' => 'Up to 250 characters length.',
            'description' => 'Short course description.',
            'code' => 'Unique symbolic code.',
            'active' => 'Whether course is active.',
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
            ['title', 'required', 'message' => self::t('{attribute} is required.')],
            [['title', 'code'], 'string', 'max' => 255, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['code', 'unique', 'message' => self::t('{attribute} already in use.')],
            ['code', 'match', 'pattern' => '/[\w\d\-_]+/i'],
            ['description', 'safe'],
            ['active', 'boolean'],
            ['active', 'default', 'value' => 1],
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
            $this->code = mb_strtoupper($this->code);
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

        $clone->code = null;

        return $clone;
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $isValid = parent::beforeDelete();

        if ($isValid) {
            // Collecting all ancestor items to delete
            $this->collectElementsToDelete();
        }

        return $isValid;
    }

    /**
     * Collects related elements to delete.
     */
    protected function collectElementsToDelete()
    {
        foreach ($this->lessons as $lesson) {
            $this->_delete['Lessons'][] = $lesson->uuid;
            $this->_delete['Workflow'][] = $lesson->workflow_uuid;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        foreach ($this->_delete as $class => $items) {
            switch ($class) {
                case 'Lessons':
                    Lesson::deleteAll(['uuid' => $items]);
                    break;
                case 'Workflow':
                    Workflow::deleteAll(['uuid' => $items]);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessons()
    {
        return $this->hasMany(Lesson::className(), ['course_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTests()
    {
        return $this->hasMany(Test::className(), ['course_uuid' => 'uuid']);
    }
}