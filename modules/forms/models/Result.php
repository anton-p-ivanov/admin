<?php

namespace forms\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Workflow;
use forms\modules\admin\modules\fields\models\Field;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class Result
 *
 * @property string $uuid
 * @property string $form_uuid
 * @property string $status_uuid
 * @property string $workflow_uuid
 *
 * @property Field[] $fields
 * @property Form $form
 * @property FormStatus $status
 * @property Workflow $workflow
*
 * @package forms\models
 */
class Result extends ActiveRecord
{
    /**
     * @var Field[]
     */
    private $_fields;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $defaultStatus = $this->form ? $this->form->getDefaultStatus() : null;
        if ($defaultStatus) {
            $this->status_uuid = $defaultStatus->uuid;
        }
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%forms_results}}';
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($params),
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
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::class;
        $behaviors[] = WorkflowBehavior::class;

        return $behaviors;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['status_uuid', 'required', 'message' => self::t('{attribute} is required.')],
            [
                'status_uuid',
                'exist',
                'targetClass' => FormStatus::class,
                'targetAttribute' => 'uuid',
                'message' => self::t('Invalid status.')
            ],
        ];
    }

    /**
     * @return array
     */
    public function transactions(): array
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
            'uuid' => 'Identifier',
            'status_uuid' => 'Status',
            'status.title' => 'Status',
            'workflow.modified_date' => 'Date',
            'workflow.created.fullname' => 'Owner'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('forms/results', $message, $params);
    }

    /**
     * @return Result|bool
     */
    public function duplicate()
    {
        $clone = new self([
            'form_uuid' => $this->form_uuid,
        ]);

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        /* @todo cloning properties */
//        $clone->data = Json::decode($this->data);

        return $clone;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        if ($this->_fields === null) {
            $this->_fields = Field::find()
                ->where(['form_uuid' => $this->form_uuid])
                ->orderBy(['sort' => SORT_ASC])
                ->indexBy('code')
                ->all();
        }

        return $this->_fields;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::class, ['uuid' => 'form_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(FormStatus::class, ['uuid' => 'status_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params)
    {
        return self::find()
            ->joinWith(['status', 'workflow'])
            ->where($params);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes(): array
    {
        $attributes = (new self())->attributes();
        $attributes['status.title'] = [
            'asc' => ['{{%forms_statuses}}.[[title]]' => SORT_ASC],
            'desc' => ['{{%forms_statuses}}.[[title]]' => SORT_DESC],
        ];
        $attributes['workflow.modified_date'] = [
            'asc' => ['{{%workflow}}.[[modified_date]]' => SORT_ASC],
            'desc' => ['{{%workflow}}.[[modified_date]]' => SORT_DESC],
        ];
        $attributes['workflow.created.fullname'] = [
            'asc' => ['{{%workflow}}.[[created_by]]' => SORT_ASC],
            'desc' => ['{{%workflow}}.[[created_by]]' => SORT_DESC],
        ];

        return $attributes;
    }
}