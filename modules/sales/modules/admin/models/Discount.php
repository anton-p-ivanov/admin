<?php
namespace sales\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;

/**
 * Class Discount
 *
 * @package sales\modules\admin\models
 */
class Discount extends \sales\models\Discount
{
    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'title' => 'Title',
            'code' => 'Code',
            'value' => 'Value',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'title' => 'Up to 200 characters length.',
            'code' => 'Unique symbolic code. Will be generated if empty.',
            'value' => 'Default discount value (in percent).',
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
            [['title', 'code'], 'string', 'max' => 200, 'tooLong' => self::t('Maximum {max, number} characters allowed.')],
            ['code', 'unique'],
            ['code', 'match', 'pattern' => '/[\w\d\_\-]+/', 'message' => self::t('Invalid characters found.')],
            ['value', 'double', 'max' => 100, 'min' => 0],
            ['value', 'default', 'value' => 0]
        ];
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::class;
        $behaviors[] = WorkflowBehavior::class;
        $behaviors[] = [
            'class' => SluggableBehavior::class,
            'attribute' => 'title',
            'slugAttribute' => 'code',
            'ensureUnique' => true,
            'immutable' => true
        ];

        return $behaviors;
    }

    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => ['title' => SORT_ASC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes()
    {
        $attributes = (new self())->attributes();
        $attributes['workflow.modified_date'] = [
            'asc' => ['{{%workflow}}.[[modified_date]]' => SORT_ASC],
            'desc' => ['{{%workflow}}.[[modified_date]]' => SORT_DESC],
        ];

        return $attributes;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        /* @var \yii\db\ActiveQuery $query */
        $query = self::find()->joinWith('workflow');

        return $query;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            $this->value = (double) $this->value / 100;
        }

        return $isValid;
    }

    /**
     * @return Discount
     */
    public function duplicate()
    {
        $copy = new self();

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        $copy->code = null;

        return $copy;
    }
}
