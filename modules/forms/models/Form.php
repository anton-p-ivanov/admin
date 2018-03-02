<?php

namespace forms\models;

use app\models\Workflow;
use forms\modules\admin\modules\fields\models\Field;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
 * @property FormResult[] $results
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
     * Using `FROM_UNIXTIME()` MySQL function to sets date attributes.
     */
    protected function parseActiveDates()
    {
        foreach ($this->active_dates as $name => $date) {
            if (is_int($date)) {
                $expression = new Expression("FROM_UNIXTIME(:$name)", [":$name" => $date]);
                $this->setAttribute($name, $expression);
            }
            else {
                $this->setAttribute($name, null);
            }
        }
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
     * @return null|ActiveRecord
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
        return $this->hasMany(FormResult::class, ['form_uuid' => 'uuid']);
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
     * @param array $dates
     * @param null|string $format
     */
    public function formatDatesArray(array $dates, $format = null)
    {
        foreach ($dates as $attribute) {
            if ($this->$attribute) {
                $this->$attribute = \Yii::$app->formatter->asDatetime($this->$attribute, $format);
            }
        }
    }
}