<?php

namespace accounts\models;


/**
 * Class Address
 *
 * @package account\models
 */
class Address extends \app\models\Address
{
    /**
     * @var string
     */
    public $account_uuid;

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            (new AccountAddress([
                'account_uuid' => $this->account_uuid,
                'address_uuid' => $this->uuid
            ]))->insert();
        }
    }
}