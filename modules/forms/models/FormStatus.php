<?php

namespace forms\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Workflow;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class FormStatus
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 * @property boolean $active
 * @property boolean $default
 * @property int $sort
 * @property string $form_uuid
 * @property string $workflow_uuid
 *
 * @property Workflow $workflow
 * @property Form $form
 *
 * @package forms\models
 */
class FormStatus extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%forms_statuses}}';
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            'default' => self::OP_ALL
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
            'sort' => 'The numerical value that determines the position of the status in various lists. Default value is `100`.',
        ];

        return array_map('self::t', $hints);
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            ['title', 'required', 'message' => self::t('Title is required.')],
            ['title', 'string', 'max' => 255, 'tooLong' => self::t('Maximum {max} characters allowed.')],
            [['active', 'default'], 'boolean'],
            ['description', 'safe'],
            ['form_uuid', 'exist', 'targetClass' => Form::className(), 'targetAttribute' => 'uuid'],
            // Sort field
            [
                'sort',
                'integer',
                'min' => 0,
                'tooSmall' => self::t('Value must be greater than 0.'),
                'message' => self::t('Value must be a integer.')
            ],
        ];

        $className = '\mail\models\Template';
        if (class_exists($className)) {
            $rules[] = [
                'mail_template_uuid',
                'exist',
                'targetClass' => $className,
                'targetAttribute' => 'uuid'
            ];
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::className();
        $behaviors[] = WorkflowBehavior::className();

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
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::className(), ['uuid' => 'form_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::className(), ['uuid' => 'workflow_uuid']);
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

        return $clone;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default === 1;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active === 1;
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
     * @param string $form_uuid
     * @return array
     */
    public static function getList($form_uuid): array
    {
        return self::find()
            ->where(['form_uuid' => $form_uuid])
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->select('title')->indexBy('uuid')->column();
    }
}