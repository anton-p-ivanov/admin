<?php

namespace fields\validators;

use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\validators\Validator;

/**
 * Class JsonValidator
 * @package ffields\validators
 */
class JsonValidator extends Validator
{
    /**
     * @var string
     */
    public $message = 'Invalid JSON-string.';

    /**
     * @param \yii\db\ActiveRecord $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        try {
            Json::decode($model->$attribute);
        }
        catch (InvalidParamException $exception) {
            $this->addError($model, $attribute, \Yii::t('fields', $this->message));
        }
    }
}