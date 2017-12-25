<?php

namespace accounts\models;

use yii\db\ActiveRecord;

/**
 * Class AccountCode
 *
 * @property string $account_uuid
 * @property string $code
 * @property boolean $valid
 * @property \DateTime $valid_date
 * @property \DateTime $issue_date
 *
 * @package account\models
 */
class AccountCode extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_codes}}';
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $isValid = $this->valid;

        if ($this->valid_date) {
            $isValid = $isValid && ((new \DateTime($this->valid_date))->getTimestamp() > (new \DateTime())->getTimestamp());
        }

        return $isValid;
    }
}