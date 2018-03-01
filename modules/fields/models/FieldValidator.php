<?php

namespace fields\models;

use fields\validators\JsonValidator;

/**
 * Class FieldValidator
 *
 * @property string $type
 * @property string $options
 * @property boolean $active
 * @property integer $sort
 *
 * @package fields\models
 */
class FieldValidator extends FieldRelation
{
    /**
     * Field validator types constants
     */
    const
        TYPE_STRING = 'S',
        TYPE_BOOLEAN = 'B',
        TYPE_DATE = 'D',
        TYPE_NUMBER = 'N',
        TYPE_EMAIL = 'E',
        TYPE_MATCH = 'M',
        TYPE_REQUIRED = 'R',
        TYPE_URL = 'W',
        TYPE_UNIQUE = 'U',
        TYPE_FILE = 'F';

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%' . \Yii::$app->controller->module->module->id . '_fields_validators}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('fields/validators', $message, $params);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['type', 'required', 'message' => self::t('{attribute} is required.')],
            [
                'sort',
                'integer',
                'min' => 0,
                'tooSmall' => self::t('Value must be greater than 0.'),
                'message' => self::t('Value must be a integer.')
            ],
            ['type', 'in', 'range' => array_keys($this->getTypes())],
            ['type', 'validateType', 'when' => function (FieldValidator $model) {
                return $model->isNewRecord;
            }],
            ['active', 'boolean'],
            ['options', 'safe'],
            ['options', JsonValidator::class]
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateType($attribute)
    {
        $count = self::find()->where([
            'field_uuid' => $this->field_uuid,
            'type' => $this->$attribute
        ])->count();

        if ($count > 0) {
            $this->addError($attribute, self::t('Validator already assigned.'));
        }
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = [
            'type' => 'Type',
            'options' => 'Options',
            'sort' => 'Sort',
            'active' => 'Active',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'type' => 'Select one of available types.',
            'options' => 'Provide valid JSON-string.',
            'sort' => 'Sorting index. Default is 100.',
            'active' => 'Whether validator is active.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        $types = [
            self::TYPE_BOOLEAN => 'Boolean',
            self::TYPE_DATE => 'Date',
            self::TYPE_NUMBER => 'Number',
            self::TYPE_EMAIL => 'E-Mail',
            self::TYPE_MATCH => 'Regular expression',
            self::TYPE_REQUIRED => 'Required',
            self::TYPE_STRING => 'String',
            self::TYPE_URL => 'Url',
            self::TYPE_UNIQUE => 'Unique',
            self::TYPE_FILE => 'File',
        ];

        return array_map('self::t', $types);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (int) $this->active === 1;
    }
}