<?php

namespace mail\models;

use app\models\Workflow;
use yii\db\ActiveRecord;

/**
 * Class Type
 *
 * @property string $uuid
 * @property string $code
 * @property string $title
 * @property string $description
 * @property string $workflow_uuid
 *
 * @property Workflow $workflow
 *
 * @package mail\models
 */
class Type extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%mail_types}}';
    }

    /**
     * @param $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('mail/types', $message, $params);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return self::find()
            ->orderBy('title')
            ->select('title')
            ->indexBy('uuid')
            ->column();
    }
}