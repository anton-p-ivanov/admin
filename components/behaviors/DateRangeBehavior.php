<?php

namespace app\components\behaviors;

use app\components\validators\DateRangeValidator;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\validators\Validator;

/**
 * Class DateRangeBehavior
 *
 * @package app\components\behaviors
 */
class DateRangeBehavior extends Behavior
{
    /**
     * @var string
     */
    public $attribute = 'dates';
    /**
     * @var array
     */
    public $targetAttributes = ['begin_date', 'end_date'];

    /**
     * @param \yii\base\Component|\yii\db\ActiveRecord $owner
     */
    public function attach($owner)
    {
        parent::attach($owner);

        $owner->validators[] = Validator::createValidator('each', $owner, $this->attribute, [
            'rule' => [
                'date',
                'format' => \Yii::$app->formatter->datetimeFormat,
                'timestampAttribute' => $this->attribute,
                'message' => \Yii::t('app', 'Invalid date format.')
            ]
        ]);

        $owner->validators[] = Validator::createValidator(DateRangeValidator::class, $owner, $this->attribute, [
            'targetAttributes' => $this->targetAttributes
        ]);
    }

    /**
     * @param null|string $format
     */
    public function formatDatesArray($format = null)
    {
        /* @var \yii\db\ActiveRecord $owner */
        $owner = $this->owner;

        foreach ($this->targetAttributes as $attribute) {
            if ($owner->$attribute) {
                $owner->$attribute = \Yii::$app->formatter->asDatetime($owner->$attribute, $format);
            }
        }
    }

    /**
     * Using `FROM_UNIXTIME()` MySQL function to sets date attributes.
     */
    public function parseActiveDates()
    {
        /* @var \yii\db\ActiveRecord $owner */
        $owner = $this->owner;

        foreach ($owner->{$this->attribute} as $name => $date) {
            if (is_int($date)) {
                $expression = new Expression("FROM_UNIXTIME(:$name)", [":$name" => $date]);
                $owner->setAttribute($name, $expression);
            }
            else {
                $owner->setAttribute($name, null);
            }
        }
    }

    /**
     * Event handler
     */
    public function beforeSave()
    {
        if (is_array($this->owner->{$this->attribute})) {
            $this->parseActiveDates();
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave'
        ];
    }
}
