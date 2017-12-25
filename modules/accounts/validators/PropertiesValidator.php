<?php
namespace accounts\validators;

use fields\models\FieldValidator;
use accounts\models\AccountData;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\validators\RequiredValidator;
use yii\validators\Validator;

/**
 * Class PropertiesValidator
 *
 * @package accounts\validators
 */
class PropertiesValidator extends Validator
{
    /**
     * Supported validators list.
     * @var array
     */
    private $_validators = [
        FieldValidator::TYPE_REQUIRED => 'yii\validators\RequiredValidator',
        FieldValidator::TYPE_BOOLEAN => 'yii\validators\BooleanValidator',
        FieldValidator::TYPE_DATE => 'yii\validators\DateValidator',
        FieldValidator::TYPE_NUMBER => 'yii\validators\NumberValidator',
        FieldValidator::TYPE_EMAIL => 'yii\validators\EmailValidator',
        FieldValidator::TYPE_MATCH => 'yii\validators\RegularExpressionValidator',
        FieldValidator::TYPE_STRING => 'yii\validators\StringValidator',
        FieldValidator::TYPE_UNIQUE => 'yii\validators\UniqueValidator',
        FieldValidator::TYPE_URL => 'yii\validators\UrlValidator',
        FieldValidator::TYPE_FILE => 'yii\validators\FileValidator',
    ];
    /**
     * Default validator messages.
     * @var array
     */
    private $_messages = [
        FieldValidator::TYPE_REQUIRED => '{attribute} is required.',
        FieldValidator::TYPE_BOOLEAN => 'Invalid value.',
        FieldValidator::TYPE_DATE => 'Invalid date value.',
        FieldValidator::TYPE_NUMBER => 'Invalid number value.',
        FieldValidator::TYPE_EMAIL => 'Invalid E-Mail.',
        FieldValidator::TYPE_MATCH => 'Value does not match the rule.',
        FieldValidator::TYPE_STRING => 'Invalid string value.',
        FieldValidator::TYPE_UNIQUE => '{value} is already exist.',
        FieldValidator::TYPE_URL => 'Invalid URL.',
        FieldValidator::TYPE_FILE => 'Invalid file.',
    ];

    /**
     * @param AccountData $model
     * @param string $attribute
     * @throws InvalidConfigException
     */
    public function validateAttribute($model, $attribute)
    {
        // Get all properties with nulled values
        $properties = ArrayHelper::map($model->getFields(), 'code', function(){ return null; });

        /* @var \accounts\modules\fields\models\Field[] $fields */
        $fields = $model->getFields();

        foreach (array_merge($properties, $model->$attribute) as $field_uuid => $value) {
            $field = $fields[$field_uuid];
            foreach ($field->fieldValidators as $validator) {
                if (!array_key_exists($validator->type, $this->_validators)) {
                    throw new InvalidConfigException('Invalid validator.');
                }

                $className = $this->_validators[$validator->type];

                /* @var Validator $object */
                $object = new $className($this->getOptions($validator));
                $object->message = $object->formatMessage(\Yii::t('accounts', $object->message), [
                    'attribute' => $field->label,
                    'value' => $value
                ]);

                if ($object instanceof RequiredValidator || !$object->isEmpty($value)) {
                    if (!$object->validate($value, $errorMessage)) {
                        $field_uuid = $attribute . '[' . $field_uuid . ']';
                        $model->addError($field_uuid, $errorMessage);
                    }
                }
            }
        }
    }

    /**
     * @param FieldValidator $validator
     * @return array
     */
    protected function getOptions($validator)
    {
        try {
            $options = Json::decode($validator->options) ?: [];
        }
        catch (\Exception $exception) {
            $options = [];
        }

        if (!array_key_exists('message', $options)) {
            $options['message'] = $this->_messages[$validator->type];
        }

        return $options;
    }
}
