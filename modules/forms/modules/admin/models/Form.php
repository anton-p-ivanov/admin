<?php

namespace forms\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Workflow;
use forms\models\FormEvent;
use forms\modules\admin\modules\fields\models\Field;
use mail\modules\admin\models\Type;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class Form
 *
 * @property FormResult[] $results
 * @property FormStatus[] $statuses
 * @property Field[] $fields
 *
 * @package forms\modules\admin\models
 */
class Form extends \forms\models\Form
{
    /**
     * @var array
     */
    private $_delete = [];
    /**
     * @var \mail\models\Type
     */
    private $_event;

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'active' => 'Specifies whether users can fill out the form.',
            'active_from_date' => 'Specifies the date since users can fill out the form.',
            'active_to_date' => 'Specifies the date since form will become blocked.',
            'title' => 'Up to 250 characters length.',
            'description' => 'Describe a purpose of the form.',
            'code' => 'Only latin letters, numbers and underscore are valid. Will be generated if empty.',
            'template' => 'Provide valid HTML layout. Field codes must be enclosed in double curly braces.',
            'template_active' => 'Use form template instead of default one.',
            'sort' => 'Sorting index. Default is 100.',
            'event' => 'Select one of available event types.',
            'mail_template_uuid' => 'Select one of available templates.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = [
            // Title validation rules
            ['title', 'required', 'message' => self::t('{attribute} is required.')],
            ['title', 'string', 'max' => 255, 'tooLong' => self::t('Maximum {max, number} characters allowed.')],
            // Code validation rules
            ['code', 'string', 'max' => 50, 'tooLong' => self::t('Maximum {max, number} characters allowed.')],
            ['code', 'match', 'pattern' => '/^[a-z_\-\d]*$/i'],
            ['code', 'unique', 'message' => self::t('Web-form with code `{value}` is already exists.')],
            // Boolean rules
            [['active', 'template_active'], 'boolean'],
            // Text fields
            [['description', 'template'], 'safe'],
            ['template', 'validateTemplate'],
            // Sort field
            [
                'sort',
                'integer',
                'min' => 0,
                'tooSmall' => self::t('Value must be greater than 0.'),
                'message' => self::t('Value must be a integer.')
            ],
            // Date fields
            ['active_dates', 'each', 'rule' => [
                'date',
                'format' => \Yii::$app->formatter->datetimeFormat,
                'timestampAttribute' => 'active_dates',
                'message' => self::t('Invalid date format.')
            ]],
            ['active_dates', 'validateDateRange'],
        ];

        $className = '\mail\models\Type';
        if (class_exists($className)) {
            $rules[] = [
                'event',
                'exist',
                'targetClass' => $className,
                'targetAttribute' => 'uuid'
            ];
            $rules[] = [
                'mail_template_uuid',
                'exist',
                'targetClass' => '\mail\models\Template',
                'targetAttribute' => 'uuid'
            ];
        }

        return $rules;
    }

    /**
     * @param string $attribute
     */
    public function validateDateRange($attribute)
    {
        $value = $this->$attribute;
        if (!empty($value['active_to_date'])
            && ($value['active_from_date'] > $value['active_to_date'])
        ) {
            $this->addError($attribute . '[active_to_date]', self::t('This date must be greater than first one.'));
        }
    }

    /**
     * Check fields` presence by their code in the form template.
     * @param string $attribute
     */
    public function validateTemplate($attribute)
    {
        $value = $this->$attribute;
        $diff = [];

        // Search for field codes
        if (preg_match_all('/{{([\w]+)}}/', $value, $matches)) {
            // List all form fields
            $fields = ArrayHelper::getColumn($this->fields, 'code');

            // Service fields
            $fields[] = Field::FORM_FIELD_AUTH;
            $fields[] = Field::FORM_FIELD_CAPTCHA;

            $diff = array_diff($matches[1], $fields);
        }

        if (count($diff) > 0) {
            $this->addError($attribute, self::t('Template contains wrong field codes.'));
        }
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $isValid = parent::beforeValidate();

        if ($isValid) {
            // Need for valid slug generation
            if (mb_strlen($this->code) > 50) {
                $this->code = mb_substr($this->code, 0, 50);
            }
        }

        return $isValid;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            if (is_array($this->active_dates)) {
                $this->parseActiveDates();
            }

            if ($this->hasAttribute('mail_template_uuid')) {
                $this->{'mail_template_uuid'} = $this->{'mail_template_uuid'} && $this->_event ? $this->{'mail_template_uuid'} : null;
            }

            // Make symbolic code uppercase
            $this->code = mb_strtoupper($this->code);
        }

        return $isValid;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            if (!$this->_event) {
                $this->insertEvent();
            }
        }
        else {
            FormEvent::deleteAll(['form_uuid' => $this->uuid]);
        }

        if ($this->_event) {
            (new FormEvent([
                'form_uuid' => $this->uuid,
                'type_uuid' => $this->_event
            ]))->insert();
        }
    }

    /**
     * Inserts a new mail type for current form.
     */
    protected function insertEvent()
    {
        $type = new Type([
            'code' => 'MAIL_TYPE_' . $this->code,
            'title' => sprintf('Web form `%s` mail event', $this->code),
        ]);

        if ($type->save()) {
            $this->_event = $type->uuid;
        }
    }

    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::class;
        $behaviors[] = WorkflowBehavior::class;
        $behaviors[] = [
            'class' => SluggableBehavior::class,
            'attribute' => 'title',
            'slugAttribute' => 'code',
            'ensureUnique' => true,
            'immutable' => true
        ];

        return $behaviors;
    }

    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        $defaultOrder = ['title' => SORT_ASC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => $defaultOrder,
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return Form|false
     */
    public function duplicate()
    {
        $copy = new self();

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        $copy->active_from_date = $this->active_from_date;
        $copy->active_to_date = $this->active_to_date;
        $copy->code = null;

        return $copy;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventRelation()
    {
        $className = '\mail\models\Type';
        return $this->hasOne($className, ['uuid' => 'type_uuid'])
            ->viaTable('{{%forms_events}}', ['form_uuid' => 'uuid']);
    }

    /**
     * @return \mail\models\Type
     */
    public function getEvent()
    {
        if ($this->_event === null) {
            $className = '\mail\models\Type';
            if (class_exists($className)) {
                $this->_event = $this->getEventRelation()->one();
            }
        }

        return $this->_event;
    }

    /**
     * @param string $type
     */
    public function setEvent($type)
    {
        $this->_event = $type;
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
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        /* @var \yii\db\ActiveQuery $query */
        $query = self::find()->joinWith('workflow');

        return $query;
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $isValid = parent::beforeDelete();

        if ($isValid) {
            // Collect relations` workflow to future delete
            $this->_delete = ArrayHelper::merge(
                ArrayHelper::getColumn($this->statuses, 'workflow_uuid'),
                ArrayHelper::getColumn($this->fields, 'workflow_uuid'),
                ArrayHelper::getColumn($this->results, 'workflow_uuid')
            );
        }

        return $isValid;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        if ($this->_delete) {
            // Delete collected relation`s workflow
            Workflow::deleteAll(['uuid' => array_filter($this->_delete, 'strlen')]);
        }
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
    public function getStatuses()
    {
        return $this->hasMany(FormStatus::class, ['form_uuid' => 'uuid']);
    }
}