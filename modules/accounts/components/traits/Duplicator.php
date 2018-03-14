<?php

namespace accounts\components\traits;

use accounts\models\Account;
use accounts\models\AccountAddress;
use accounts\models\AccountDiscount;
use accounts\models\AccountProperty;
use accounts\models\AccountStatus;

/**
 * Trait Duplicator
 *
 * @package accounts\components\traits
 */
trait Duplicator
{
    /**
     * @param Account $model
     * @param Account $original
     */
    public function cloneAddresses($model, $original)
    {
        $addresses = AccountAddress::findAll(['account_uuid' => $original->uuid]);
        foreach ($addresses as $relation) {
            $this->cloneAddress($relation->address, $model->uuid);
        }
    }

    /**
     * @param Account $model
     * @param Account $original
     * @return bool|int
     */
    public function cloneProperties($model, $original)
    {
        $insert = [];
        $properties = AccountProperty::findAll(['account_uuid' => $original->uuid]);

        foreach ($properties as $property) {
            $insert[] = [
                'account_uuid' => $model->uuid,
                'field_uuid' => $property->field_uuid,
                'value' => $property->value
            ];
        }

        if ($insert) {
            return \Yii::$app->db
                ->createCommand()
                ->batchInsert(AccountProperty::tableName(), array_keys($insert[0]), $insert)
                ->execute();
        }

        return false;
    }

    /**
     * @param \accounts\models\AccountRelation $model
     * @param $uuid
     * @return bool
     */
    public function cloneRelation($model, $uuid)
    {
        $clone = $model->duplicate();
        $clone->account_uuid = $uuid;
        $isSaved = $clone->save();

        if ($isSaved && ($clone instanceof AccountStatus)) {
            $discounts = AccountDiscount::findAll(['status_uuid' => $model->uuid]);
            foreach ($discounts as $discount) {
                $isSaved = $isSaved && $this->cloneDiscount($discount, $clone->uuid);
            }
        }

        return $isSaved;
    }

    /**
     * @param \accounts\models\AccountDiscount $model
     * @param string $uuid
     * @return bool
     */
    protected function cloneDiscount($model, $uuid)
    {
        $clone = $model->duplicate();
        $clone->status_uuid = $uuid;
        $clone->value = $model->value * 100;

        return $clone->save();
    }

    /**
     * @param \accounts\models\Address $model
     * @param string $uuid
     * @return bool
     */
    protected function cloneAddress($model, $uuid)
    {
        $clone = $model->duplicate();
        $clone->account_uuid = $uuid;

        return $clone->save();
    }
}