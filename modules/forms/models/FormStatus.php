<?php

namespace forms\models;

use app\models\Workflow;
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
 * @property string $mail_template_uuid
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
     * @param $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('forms/statuses', $message, $params);
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
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
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
     * @return bool
     */
    public function hasTemplate()
    {
        return $this->mail_template_uuid !== null;
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