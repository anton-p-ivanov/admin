<?php

namespace fields\models;

/**
 * Class FieldValue
 *
 * @property string $value
 * @property string $label
 *
 * @package fields\models
 */
class FieldValue extends FieldRelation
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%' . \Yii::$app->controller->module->module->id . '_fields_values}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                ['value', 'label'],
                'required',
                'message' => self::t('{attribute} is required.')
            ],
            [
                'sort',
                'integer',
                'min' => 0,
                'tooSmall' => self::t('Value must be greater than 0.'),
                'message' => self::t('Value must be a integer.')
            ],
            [
                ['label', 'value'],
                'string',
                'max' => 250,
                'tooLong' => self::t('Maximum {max, number} characters allowed.')
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = [
            'value' => 'Value',
            'label' => 'Label',
            'sort' => 'Sort'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'value' => 'Up to 250 characters length.',
            'label' => 'Text displayed in values` list.',
            'sort' => 'Position of the value in values` list.',
        ];

        return array_map('self::t', $hints);
    }
}