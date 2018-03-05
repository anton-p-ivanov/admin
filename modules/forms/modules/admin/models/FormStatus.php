<?php

namespace forms\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use yii\data\ActiveDataProvider;

/**
 * Class FormStatus
 *
 * @package forms\modules\admin\models
 */
class FormStatus extends \forms\models\FormStatus
{
    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = [
            'title' => 'Title',
            'active' => 'Active',
            'default' => 'Default',
            'description' => 'Description',
            'sort' => 'Sort',
            'workflow.modified_date' => 'Modified',
            'mail_template_uuid' => 'Mail template',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'title' => 'Up to 250 characters length.',
            'active' => 'Status is used for processing form results.',
            'default' => 'Assign this status for each new form result by default.',
            'description' => 'Describe purpose of status.',
            'sort' => 'Sorting index. Default is 100.',
            'mail_template_uuid' => 'Select one of available templates.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['title', 'required', 'message' => self::t('{attribute} is required.')],
            [
                'title',
                'string',
                'max' => 255,
                'tooLong' => self::t('Maximum {max, number} characters allowed.')
            ],
            ['default', 'boolean'],
            ['description', 'safe'],
            ['form_uuid', 'exist', 'targetClass' => Form::class, 'targetAttribute' => 'uuid'],
            [
                'sort',
                'integer',
                'min' => 0,
                'tooSmall' => self::t('Value must be greater or equal than {min, number}.'),
                'message' => self::t('Value must be a integer.')
            ],
            [
                'mail_template_uuid',
                'exist',
                'targetClass' => '\mail\models\Template',
                'targetAttribute' => 'uuid',
                'message' => self::t('Invalid template selected.')
            ],
            ['active', 'default', 'value' => true],
            ['mail_template_uuid', 'default', 'value' => null]
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::class;
        $behaviors[] = WorkflowBehavior::class;

        return $behaviors;
    }

    /**
     * @param $form_uuid
     * @return ActiveDataProvider
     */
    public static function search($form_uuid)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($form_uuid),
            'pagination' => ['defaultPageSize' => 10],
            'sort' => [
                'defaultOrder' => ['workflow.modified_date' => SORT_DESC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    public static function getSortAttributes()
    {
        $attributes = (new self())->attributes();
        $attributes['workflow.modified_date'] = [
            'asc' => ['{{%workflow}}.[[modified_date]]' => SORT_ASC],
            'desc' => ['{{%workflow}}.[[modified_date]]' => SORT_DESC],
        ];

        return $attributes;
    }

    /**
     * @param $form_uuid
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($form_uuid)
    {
        return self::find()->joinWith('workflow')->where(['form_uuid' => $form_uuid]);
    }

    /**
     * @return FormStatus|bool
     */
    public function duplicate()
    {
        $clone = new self();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        $clone->form_uuid = $this->form_uuid;

        return $clone;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            if ($this->isDefault()) {
                self::updateAll(['default' => 0], ['form_uuid' => $this->form_uuid]);
            }
        }

        return $isValid;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::class, ['uuid' => 'form_uuid']);
    }
}