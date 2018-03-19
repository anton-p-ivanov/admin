<?php

namespace forms\models;

use app\models\Workflow;
use forms\modules\admin\modules\fields\models\Field;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class Form
 * @property string $uuid
 * @property string $code
 * @property string $title
 * @property string $description
 * @property string $template
 * @property boolean $template_active
 * @property boolean $active
 * @property string $active_from_date
 * @property string $active_to_date
 * @property integer $sort
 * @property string $workflow_uuid
 *
 * @property Workflow $workflow
 * @property Result[] $results
 * @property FormStatus[] $statuses
 * @property Field[] $fields
 *
 * @package forms\models
 */
class Form extends ActiveRecord
{
    /**
     * @var array
     */
    public $active_dates = [];

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%forms}}';
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'active' => 'In use',
            'active_from_date' => 'Enable from',
            'active_to_date' => 'Enable to',
            'title' => 'Title',
            'description' => 'Description',
            'code' => 'Code',
            'template' => 'Template code',
            'template_active' => 'Use form template',
            'sort' => 'Sort',
            'workflow.modified_date' => 'Modified',
            'results' => 'Results',
            'event' => 'Mail event type',
            'mail_template_uuid' => 'Mail template',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @param $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('forms', $message, $params);
    }

    /**
     * @return null|ActiveRecord|FormStatus
     */
    public function getDefaultStatus()
    {
        return $this->getStatuses()->andWhere(['default' => true])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::class, ['form_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::class, ['form_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatuses()
    {
        return $this->hasMany(FormStatus::class, ['form_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $isActive = $this->active === 1;

        if ($this->active_from_date) {
            $isActive = $isActive && (new \DateTime($this->active_from_date))->getTimestamp() < time();
        }

        if ($this->active_to_date) {
            $isActive = $isActive && (new \DateTime($this->active_to_date))->getTimestamp() > time();
        }

        return $isActive;
    }

    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        $defaultOrder = ['title' => SORT_ASC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => $defaultOrder,
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
}