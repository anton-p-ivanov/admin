<?php

namespace app\components\validators;

use yii\validators\Validator;

/**
 * Class DateRangeValidator
 *
 * @package app\components\validators
 */
class DateRangeValidator extends Validator
{
    /**
     * @var array
     */
    public $targetAttributes = ['begin_date', 'end_date'];

    /**
     * @param \yii\db\ActiveRecord $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        $firstValue = $value[$this->targetAttributes[0]];
        $lastValue = $value[$this->targetAttributes[1]];

        if (!empty($lastValue) && ($firstValue > $lastValue)) {
            $model->addError(
                $attribute . '[' . $this->targetAttributes[1] . ']',
                \Yii::t('app', '{last} must be greater than {first}.', [
                    'first' => $model->getAttributeLabel($this->targetAttributes[1]),
                    'last' => $model->getAttributeLabel($this->targetAttributes[0]),
                ])
            );
        }
    }
}