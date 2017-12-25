<?php

namespace accounts\models;

use yii\db\ActiveRecord;

/**
 * Class AccountType
 *
 * @property string $account_uuid
 * @property string $type_uuid
 *
 * @package account\models
 */
class AccountType extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_types_assignments}}';
    }
}