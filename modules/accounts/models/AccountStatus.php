<?php
namespace accounts\models;

use yii\db\ActiveRecord;

/**
 * Class AccountSite
 *
 * @property string $account_uuid
 * @property string $status_uuid
 *
 * @package accounts\models
 */
class AccountStatus extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%partnership_accounts}}';
    }
}
