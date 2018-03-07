<?php

namespace fields\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Workflow;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class Group
 *
 * @property string $uuid
 * @property string $title
 * @property boolean $active
 * @property integer $sort
 * @property string $workflow_uuid
 *
 * @property Field[] $fields
 * @property Workflow $workflow
 *
 * @package fields\models
 */
class Group extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_fields_groups}}';
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params = [])
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($params),
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
        return (new static())->attributes();
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params)
    {
        return static::find()->where($params);
    }

    /**
     * @param array $params
     * @return array
     */
    public static function getList($params = [])
    {
        return self::find()
            ->where($params)
            ->select('title')
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->indexBy('uuid')
            ->column();
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('fields/groups', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'active' => 'Active',
            'sort' => 'Sort',
            'title' => 'Title',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'active' => 'Whether group is active.',
            'sort' => 'Sorting index. Default is 100.',
            'title' => 'Up to 255 characters length.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = WorkflowBehavior::class;
        $behaviors[] = PrimaryKeyBehavior::class;

        return $behaviors;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['title', 'required', 'message' => self::t('{attribute} is required.')],
            ['title', 'string', 'max' => 255, 'tooLong' => 'Maximum {max, number} characters allowed.'],
            ['active', 'boolean'],
            ['sort', 'integer', 'min' => 0, 'tooSmall' => '{attribute} value must be greater or equal {min, number}.'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**+
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::class, ['group_uuid' => 'uuid']);
    }

    /**
     * @return self
     */
    public function duplicate()
    {
        $clone = new static();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        return $clone;
    }
}