<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class WorkflowStatus
 * @property string $code
 * @property string $title
 */
class WorkflowStatus extends ActiveRecord
{
    /**
     * Workflow status constants
     */
    const
        WORKFLOW_STATUS_DEFAULT = 'D',
        WORKFLOW_STATUS_READY = 'R',
        WORKFLOW_STATUS_PUBLISHED = 'P';

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%workflow_statuses}}';
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return self::find()
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->indexBy('code')
            ->select('title')
            ->column();
    }

}
