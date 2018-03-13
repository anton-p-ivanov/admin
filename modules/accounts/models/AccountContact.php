<?php

namespace accounts\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class AccountContact
 *
 * @property string $uuid
 * @property string $account_uuid
 * @property string $user_uuid
 * @property string $email
 * @property string $fullname
 * @property string $position
 * @property integer $sort
 *
 * @package account\models
 */
class AccountContact extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_contacts}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('accounts/contacts', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'user_uuid' => 'User account',
            'fullname' => 'Full name',
            'email' => 'E-Mail',
            'position' => 'Position',
            'sort' => 'Sort',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'user_uuid' => 'Select a user account if available.',
            'fullname' => 'First, last and middle (optional) user names.',
            'email' => 'Provide valid E-Mail address.',
            'position' => 'Contact job position.',
            'sort' => 'Sorting index. Default is 100.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                ['fullname', 'email', 'position'],
                'required',
                'message' => self::t('{attribute} is required.')
            ],
            [
                'user_uuid',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'uuid',
                'message' => self::t('Invalid user account.')
            ],
            [
                'email',
                'email'
            ],
            [
                'email',
                'validateUnique'
            ],
            [
                ['fullname', 'position'],
                'string',
                'max' => 255,
                'tooLong' => self::t('Maximum {max, number} characters allowed.')
            ],
            [
                'sort',
                'integer',
                'min' => 0,
                'tooSmall' => self::t('{attribute} value must be greater or equal than {min, number}.')
            ],
            [
                'user_uuid',
                'default',
                'value' => null
            ],
            [
                'sort',
                'default',
                'value' => 100
            ]
        ];
    }

    /**
     * @param $attribute
     */
    public function validateUnique($attribute)
    {
        $count = self::find()->where(['email' => $this->$attribute])->count();

        if ($count > 0) {
            $this->addError($attribute, self::t('Contact has already exists.'));
        }
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
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params)
    {
        $defaultOrder = ['sort' => SORT_ASC, 'fullname' => SORT_ASC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($params),
            'sort' => [
                'defaultOrder' => $defaultOrder,
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes()
    {
        return (new self())->attributes();
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params)
    {
        return self::find()->where($params);
    }

    /**
     * @return AccountContact
     */
    public function duplicate()
    {
        $copy = new self([
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
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            $user = User::findOne(['email' => $this->email]);

            if ($user) {
                $this->user_uuid = $user->uuid;
            }
        }

        return $isValid;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['uuid' => 'user_uuid']);
    }

    /**
     * @return bool
     */
    public function hasUser()
    {
        return $this->user_uuid !== null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['uuid' => 'account_uuid']);
    }
}