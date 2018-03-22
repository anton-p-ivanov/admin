<?php
namespace app\models;

use accounts\models\Account;
use users\models\UserAccount;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 *
 * Attributes:
 * @property string $uuid
 * @property string $email
 * @property string $fname
 * @property string $lname
 * @property string $sname
 * @property string $workflow_uuid
 *
 * @property Account $account
 *
 * @package app\models
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @var User|null
     */
    private static $_identity;

    /**
     * @param int $id
     * @return User|null
     */
    public static function findIdentity($id)
    {
        if (self::$_identity === null) {
            self::$_identity = self::findOne($id);
        }

        return self::$_identity;
    }

    /**
     * @param string $token
     * @param string|null $type
     * @return User|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return self::findOne(['email' => 'guest.user@example.com'])->uuid;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return sha1($this->uuid);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fname . ' ' . $this->lname;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['uuid' => 'account_uuid'])
            ->viaTable(UserAccount::tableName(), ['user_uuid' => 'uuid']);
    }
}
