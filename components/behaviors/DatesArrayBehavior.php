<?php

namespace app\components\behaviors;

use app\components\validators\DateRangeValidator;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\validators\Validator;

/**
 * Class DatesArrayBehavior
 *
 * @package app\components\behaviors
 */
class DatesArrayBehavior extends Behavior
{
    /**
     * @var string
     */
    public $attribute = 'dates';
    /**
     * @var array
     */
    public $targetAttributes = ['begin_date', 'end_date'];
//    /**
//     * @var mixed
//     */
//    private $_dates = [];
//
//    /**
//     * @param string $name
//     * @return mixed
//     */
//    public function __get($name)
//    {
//        if ($name == $this->attribute) {
//            return array_key_exists($name, $this->_dates) ? $this->_dates[$name] : null;
//        }
//
//        return parent::__get($name);
//    }
//
//    /**
//     * @param string $name
//     * @param mixed $value
//     */
//    public function __set($name, $value)
//    {
//        if ($name === $this->attribute) {
//            $this->_dates[$name] = $value;
//        }
//
//        parent::__set($name, $value);
//    }

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
     * @param array $dates
     * @param null|string $format
     */
    public function formatDatesArray(array $dates, $format = null)
    {
        /* @var \yii\db\ActiveRecord $owner */
        $owner = $this->owner;

        foreach ($dates as $attribute) {
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
