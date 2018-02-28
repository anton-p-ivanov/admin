<?php
namespace partnership\models;

/**
 * Class StatusDiscount
 *
 * @property Status $status
 *
 * @package partnership\models
 */
class StatusDiscount extends \sales\modules\discounts\models\StatusDiscount
{
    /**
     * @var string
     */
    public static $statusModel = Status::class;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%sales_discounts_statuses}}';
    }
}
