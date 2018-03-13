<?php

namespace accounts\models;
use app\models\AddressType;


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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $defaultType = AddressType::getDefault();
        if ($defaultType) {
            $this->type_uuid = $defaultType->uuid;
        }
    }

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

    /**
     * @return Address
     */
    public function duplicate()
    {
        $copy = new self();

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        $copy->account_uuid = AccountAddress::find()
            ->where(['address_uuid' => $this->uuid])
            ->select('account_uuid')
            ->scalar();

        return $copy;
    }
}