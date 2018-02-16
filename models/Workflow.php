<?php
namespace app\models;

use app\components\behaviors\PrimaryKeyBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class Workflow
 * @property string $uuid
 * @property string $status
 * @property string $created_by
 * @property string $modified_by
 * @property \DateTime $modified_date
 * @property \DateTime $created_date
 * @property boolean $removed
 *
 * @property User $created
 * @property User $modified
 *
 * @package app\models
 */
class Workflow extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%workflow}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->status = WorkflowStatus::WORKFLOW_STATUS_DEFAULT;
        }
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('workflow', $message, $params);
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
    public function rules()
    {
        return [
            ['status', 'in', 'range' => array_keys(WorkflowStatus::getList())],
            ['status', 'default', 'value' => WorkflowStatus::WORKFLOW_STATUS_DEFAULT]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'status' => 'Status',
            'modified_date' => 'Modified'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'status' => 'Element`s workflow status.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($result = parent::beforeSave($insert)) {
            $user_id = \Yii::$app->user->isGuest ? null : \Yii::$app->user->id;
            $date = new Expression('NOW()');

            if ($insert) {
                $this->created_by = $user_id;
                $this->created_date = $date;
            }

            $this->modified_by = $user_id;
            $this->modified_date = $date;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::className();

        return $behaviors;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreated()
    {
        return $this->hasOne(User::className(), ['uuid' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModified()
    {
        return $this->hasOne(User::className(), ['uuid' => 'modified_by']);
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == WorkflowStatus::WORKFLOW_STATUS_PUBLISHED;
    }
}
