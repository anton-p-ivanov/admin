<?php
namespace users\models;

use accounts\models\Account;
use app\components\behaviors\PrimaryKeyBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class UserAccount
 *
 * @property string $uuid
 * @property string $user_uuid
 * @property string $account_uuid
 * @property string $position
 *
 * @property Account $account
 *
 * @package users\models
 */
class UserAccount extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_accounts}}';
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
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors[] = PrimaryKeyBehavior::class;

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = [
            'account_uuid' => 'Account',
            'position' => 'Position',
            'account.title' => 'Account'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'account_uuid' => 'Select one of available accounts.',
            'position' => 'Name your account position.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['account_uuid', 'required', 'message' => self::t('{attribute} is required.')],
            ['account_uuid', 'exist', 'targetClass' => Account::class, 'targetAttribute' => 'uuid', 'message' => self::t('Invalid account selected.')],
            ['account_uuid', 'unique', 'targetAttribute' => ['account_uuid', 'user_uuid'], 'message' => self::t('Account already linked.')],
            ['position', 'string', 'tooLong' => self::t('Maximum {max, number} characters allowed.')],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['uuid' => 'account_uuid']);
    }

    /**
     * @param string $user_uuid
     * @return ActiveDataProvider
     */
    public static function search($user_uuid)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($user_uuid),
            'pagination' => ['defaultPageSize' => 5],
            'sort' => [
                'defaultOrder' => ['account.title' => SORT_ASC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes(): array
    {
        $attributes = (new self())->attributes();
        $attributes['account.title'] = [
            'asc' => ['{{%accounts}}.[[title]]' => SORT_ASC],
            'desc' => ['{{%accounts}}.[[title]]' => SORT_DESC],
        ];

        return $attributes;
    }

    /**
     * @param string $user_uuid
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($user_uuid)
    {
        return self::find()->where(['user_uuid' => $user_uuid])->joinWith('account');
    }

    /**
     * @return UserAccount
     */
    public function duplicate()
    {
        $copy = new self([
            'user_uuid' => $this->user_uuid
        ]);

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        return $copy;
    }
}
