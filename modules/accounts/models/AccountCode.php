<?php

namespace accounts\models;

use app\components\behaviors\PrimaryKeyBehavior;
use mail\models\Template;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class AccountCode
 *
 * @property string $uuid
 * @property string $account_uuid
 * @property string $code
 * @property boolean $valid
 * @property \DateTime $valid_date
 * @property \DateTime $issue_date
 *
 * @property Account $account
 *
 * @package account\models
 */
class AccountCode extends ActiveRecord
{
    /**
     * @var string
     */
    public $registrationCode;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_codes}}';
    }

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
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['uuid' => 'account_uuid']);
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

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid && $insert) {

            // Invalidate all issued account codes
            self::updateAll(['valid' => false], ['account_uuid' => $this->account_uuid]);

            // Generate and save a new one
            $this->registrationCode = \Yii::$app->security->generateRandomString(10);

            $this->code = \Yii::$app->security->generatePasswordHash($this->registrationCode);
            $this->valid = true;
            $this->valid_date = new Expression('DATE_ADD(NOW(), INTERVAL 1 YEAR)');
        }

        return $isValid;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->sendNotification();
    }

    /**
     * @return bool
     */
    protected function sendNotification()
    {
        $params = $this->account->attributes;
        $params['REGISTRATION_CODE'] = $this->registrationCode;

        foreach ($params as $key => $value) {
            $params['ACCOUNT_' . strtoupper($key)] = $value;
            unset($params[$key]);
        }

        return Template::send('ACCOUNT_REGISTRATION_CODE_UPDATED_ADMIN', $params);
    }
}