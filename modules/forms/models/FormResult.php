<?php

namespace forms\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Workflow;
use forms\modules\fields\models\Field;
use forms\validators\PropertiesValidator;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class FormResult
 *
 * @property string $uuid
 * @property string $data
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
class FormResult extends ActiveRecord
{
    /**
     * @var Field[]
     */
    private $_fields;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%forms_results}}';
    }

    /**
     * @param string $form_uuid
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
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::className();
        $behaviors[] = WorkflowBehavior::className();

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
                'targetClass' => FormStatus::className(),
                'targetAttribute' => 'uuid',
                'message' => self::t('Invalid status.')
            ],
            ['data', 'safe'],
            ['data', PropertiesValidator::className()]
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
            'workflow.modified_date' => 'Modified'
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
        return \Yii::t('forms', $message, $params);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($isValid = parent::beforeSave($insert)) {
            if (is_array($this->data)) {
                $this->data = Json::encode($this->data);
            }
        }

        return $isValid;
    }

    /**
     * @return FormResult|bool
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

        $clone->data = Json::decode($this->data);

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
                /*->with('fieldValidators')*/
                ->all();
        }

        return $this->_fields;
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
    public function getStatus()
    {
        return $this->hasOne(FormStatus::className(), ['uuid' => 'status_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::className(), ['uuid' => 'workflow_uuid']);
    }

    /**
     * @param string $form_uuid
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($form_uuid)
    {
        return self::find()
            ->joinWith(['status', 'workflow'])
            ->where(['{{%forms_results}}.[[form_uuid]]' => $form_uuid]);
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

        return $attributes;
    }
}