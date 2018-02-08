<?php
namespace app\models;

use app\components\traits\MultilingualActiveRecord;
use i18n\components\MultilingualBehavior;
use yii\db\ActiveRecord;

/**
 * Class WorkflowStatus
 * @property string $code
 * @property string $title
 */
class WorkflowStatus extends ActiveRecord
{
    use MultilingualActiveRecord;

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
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['ml'] = [
            'class' => MultilingualBehavior::className(),
            'langForeignKey' => 'code',
            'tableName' => '{{%workflow_statuses_i18n}}',
            'attributes' => ['title']
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return self::find()
            ->joinWith('translation')
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->indexBy('code')
            ->select('title')
            ->column();
    }

}
