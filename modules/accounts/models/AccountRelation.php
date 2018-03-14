<?php

namespace accounts\models;

use app\components\behaviors\PrimaryKeyBehavior;
use yii\db\ActiveRecord;

/**
 * Class AccountRelation
 *
 * @property string $uuid
 * @property string $account_uuid
 * @property integer $sort
 *
 * @package account\models
 */
class AccountRelation extends ActiveRecord
{
    /**
     * @return array
     */
    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_ALL];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::class;

        return $behaviors;
    }

    /**
     * @return AccountRelation
     */
    public function duplicate()
    {
        $copy = new static([
            'account_uuid' => $this->account_uuid
        ]);

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        return $copy;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['uuid' => 'account_uuid']);
    }
}