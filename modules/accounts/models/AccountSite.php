<?php
namespace accounts\models;

use yii\db\ActiveRecord;

/**
 * Class AccountSite
 *
 * @property string $account_uuid
 * @property string $site_uuid
 *
 * @package accounts\models
 */
class AccountSite extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_sites}}';
    }
}
