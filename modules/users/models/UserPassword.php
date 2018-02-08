<?php
namespace users\models;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class UserPassword
 *
 * @property string $user_uuid
 * @property string $password
 * @property string $salt
 * @property \DateTime $created_date
 * @property \DateTime $expired_date
 * @property bool $expired
 *
 * @package users\models
 */
class UserPassword extends ActiveRecord
{
    const SCENARIO_NEW_USER = 'new_user';
    /**
     * @var string
     */
    public $password_new;
    /**
     * @var string
     */
    public $password_new_repeat;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_passwords}}';
    }

    /**
     * @param $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('users', $message, $params);
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
    public function attributeLabels(): array
    {
        $labels = [
            'password_new' => 'New password',
            'password_new_repeat' => 'Repeat new password',
            'created_date' => 'Issue date',
            'expired_date' => 'Expiration date'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'password_new' => 'Minimum 8 characters are required. If no password is set it will be generated automatically.',
            'password_new_repeat' => 'Please, repeat new password.',
            'expired_date' => 'Set an expiration date for this password.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['password_new', 'required', 'on' => self::SCENARIO_NEW_USER, 'message' => self::t('{attribute} is required.')],
            ['password_new', 'string', 'min' => 8, 'message' => self::t('Minimum {min, number} characters allowed.')],
            ['password_new', 'compare', 'message' => self::t('Passwords does not match.')],
            ['password_new_repeat', 'safe'],
            [
                'expired_date',
                'date',
                'format' => \Yii::$app->formatter->datetimeFormat,
                'timestampAttribute' => 'expired_date',
                'message' => self::t('Invalid date format.')
            ]
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {

            // Parse expiration date
            $this->parseExpiredDate();

            if ($insert) {
                $this->password_new = $this->password_new ?: \Yii::$app->security->generateRandomString(8);

                $this->created_date = new Expression('NOW()');
                $this->expired = 0;
                $this->salt = \Yii::$app->security->generateRandomString(20);
                $this->password = \Yii::$app->security->generatePasswordHash(self::password(
                    $this->password_new,
                    $this->salt
                ));

                // Expire previous user`s passwords
                self::updateAll(['expired' => 1], ['user_uuid' => $this->user_uuid]);

                // Clearing user checkwords if exists
                UserCheckword::deleteAll(['user_uuid' => $this->user_uuid]);
            }
        }

        return $isValid;
    }

    /**
     * @param string $password
     * @param string $salt
     * @return string
     */
    public static function password($password, $salt)
    {
        return \Yii::$app->params['salt'] . $password . $salt;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        $isExpired = $this->expired === 1;

        if ($this->expired_date) {
            $isExpired = $isExpired && (new \DateTime($this->expired_date))->getTimestamp() > time();
        }

        return $isExpired;
    }

    /**
     * @param string $format
     */
    public function formatExpiredDate($format = null)
    {
        if ($this->expired_date) {
            $this->expired_date = \Yii::$app->formatter->asDatetime($this->expired_date, $format);
        }
    }

    /**
     * Using `FROM_UNIXTIME()` MySQL function to sets date attributes.
     */
    protected function parseExpiredDate()
    {
        if (is_int($this->expired_date)) {
            $expression = new Expression("FROM_UNIXTIME(:expired_date)", [":expired_date" => $this->expired_date]);
        }
        else {
            $expression = new Expression('DATE_ADD(NOW(), INTERVAL 1 YEAR)');
        }

        $this->expired_date = $expression;
    }
}
