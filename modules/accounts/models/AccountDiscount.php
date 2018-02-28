<?php
namespace accounts\models;

use sales\modules\discounts\models\StatusDiscount;

/**
 * Class AccountDiscount
 *
 * @property AccountStatus $status
 *
 * @package accounts\models
 */
class AccountDiscount extends StatusDiscount
{
    /**
     * @var string
     */
    public static $statusModel = AccountStatus::class;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%sales_discounts_accounts}}';
    }
}
