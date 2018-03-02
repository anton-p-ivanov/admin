<?php

namespace mail\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;
use yii\validators\UniqueValidator;

/**
 * Class Type
 *
 * @package mail\modules\admin\models
 */
class Type extends \mail\models\Type
{
    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = [
            'code' => 'Code',
            'title' => 'Title',
            'description' => 'Description',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'title' => 'Up to 250 characters length.',
            'code' => 'Only latin letters, digits, dash and underscore characters are valid. Will be generated if empty.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['title', 'required', 'message' => self::t('{attribute} is required.')],
            ['code', 'string', 'max' => 100, 'tooLong' => self::t('Maximum (max, number) characters allowed.')],
            ['title', 'string', 'max' => 250, 'tooLong' => self::t('Maximum (max, number) characters allowed.')],
            ['description', 'safe'],
            ['code', 'unique', 'message' => self::t('Type with code `{value}` is already exist.')]
        ];
    }

    /**
     * @return array
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::class;
        $behaviors[] = WorkflowBehavior::class;
        $behaviors[] = [
            'class' => SluggableBehavior::class,
            'attribute' => 'title',
            'slugAttribute' => 'code',
            'ensureUnique' => true,
            'uniqueValidator' => ['class' => UniqueValidator::class],
            'immutable' => true
        ];

        return $behaviors;
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $isValid = parent::beforeValidate();

        if ($isValid) {
            // Need for valid slug generation
            if (mb_strlen($this->code) > 100) {
                $this->code = mb_substr($this->code, 0, 100);
            }
        }

        return $isValid;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            // Make symbolic code uppercase
            $this->code = mb_strtoupper($this->code);
        }

        return $isValid;
    }

    /**
     * @return Type
     */
    public function duplicate()
    {
        $copy = new self();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $copy->$attribute = $value;
            }
        }

        $copy->code = null;

        return $copy;
    }

    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        $defaultOrder = ['workflow.modified_date' => SORT_DESC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => $defaultOrder,
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        return self::find()->joinWith('workflow');
    }

    /**
     * @return array
     */
    protected static function getSortAttributes(): array
    {
        return [
            'title',
            'code',
            'workflow.modified_date' => [
                'asc' => ['{{%workflow}}.[[modified_date]]' => SORT_ASC],
                'desc' => ['{{%workflow}}.[[modified_date]]' => SORT_DESC],
            ],
        ];
    }
}