<?php

namespace accounts\models;

use app\models\User;
use yii\data\ActiveDataProvider;

/**
 * Class AccountManager
 *
 * @property string $uuid
 * @property string $account_uuid
 *
 * @property User $manager
 * @property Account $account
 *
 * @package account\models
 */
class AccountManager extends AccountRelation
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_managers}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('accounts/managers', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'manager_uuid' => 'Manager',
            'comments' => 'Comments',
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
            'manager_uuid' => 'Select a user account.',
            'comments' => 'Tell something about this manager.',
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
                'manager_uuid',
                'required',
                'message' => self::t('{attribute} is required.')
            ],
            [
                'manager_uuid',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'uuid',
                'message' => self::t('Invalid manager account.')
            ],
            [
                'manager_uuid',
                'unique',
                'targetAttribute' => ['account_uuid', 'manager_uuid'],
                'message' => self::t('This manager already assigned.')
            ],
            [
                'sort',
                'integer',
                'min' => 0,
                'tooSmall' => self::t('{attribute} value must be greater or equal than {min, number}.')
            ],
            [
                'comments',
                'safe',
            ],
            [
                'sort',
                'default',
                'value' => 100
            ]
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params)
    {
        $defaultOrder = ['sort' => SORT_ASC];

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
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(User::class, ['uuid' => 'manager_uuid']);
    }
}