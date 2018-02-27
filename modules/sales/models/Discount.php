<?php
namespace sales\models;

use app\models\Workflow;
use yii\db\ActiveRecord;

/**
 * Class Discount
 *
 * @property string $uuid
 * @property string $code
 * @property string $title
 * @property double $value
 * @property string $workflow_uuid
 *
 * @property Workflow $workflow
 *
 * @package sales\models
 */
class Discount extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%sales_discounts}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('sales/discounts', $message, $params);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }
}
