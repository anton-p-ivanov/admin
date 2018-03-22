<?php
namespace users\models;

use accounts\models\Account;
use app\models\AuthItem;
use app\models\Filter;
use app\models\Site;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class UserFilter
 *
 * @property string $uuid
 * @property string $query
 * @property string $hash
 *
 * @property Account $account
 *
 * @package users\models
 */
class UserFilter extends Filter
{
    /**
     * @var string
     */
    public $owner;
    /**
     * @var string
     */
    public $fullname;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $account_uuid;
    /**
     * @var string
     */
    public $role;
    /**
     * @var string
     */
    public $site;
    /**
     * @var Account
     */
    private $_account;

    /**
     * @param \yii\db\ActiveQuery $query
     */
    public function buildQuery(&$query)
    {
        try {
            $attributes = array_filter(Json::decode($this->query), function ($attribute) {
                return !empty($attribute);
            });

            $this->isActive = true;
        }
        catch (\Exception $exception) {
            $attributes = [];
        }

        foreach ($attributes as $attribute => $value) {
            switch ($attribute) {
                case 'owner':
                    $query->andFilterWhere(['{{%workflow}}.[[created_by]]' => $value]);
                    break;
                case 'email':
                    $query->andFilterWhere(['like', 'title', $value]);
                    break;
                case 'fullname':
                    $query->andFilterWhere(['like', 'CONCAT_WS(" ", `fname`, `lname`)', $value]);
                    break;
                case 'account_uuid':
                    $query->joinWith('accounts')->andFilterWhere(['{{%users_accounts}}.[[account_uuid]]' => $value]);
                    break;
                case 'role':
                    $query->joinWith('roles')->andFilterWhere(['{{%auth_assignments}}.[[item_name]]' => $value]);
                    break;
                case 'site':
                    $query->joinWith('sites')->andFilterWhere(['{{%users_sites}}.[[site_uuid]]' => $value]);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'owner' => 'Owner',
            'fullname' => 'Fullname',
            'email' => 'E-Mail',
            'account_uuid' => 'Account',
            'role' => 'Role',
            'site' => 'Site',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'owner' => 'User who created the element.',
            'fullname' => 'User name or its part.',
            'email' => 'User E-Mail or its part.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['owner', 'in', 'range' => array_keys(self::getOwners()), 'message' => self::t('Invalid {attribute} value.')],
            [['email', 'fullname'], 'string', 'max' => 255, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['account_uuid', 'exist', 'targetClass' => Account::class, 'targetAttribute' => 'uuid', 'message' => self::t('Invalid {attribute} value.')],
            ['role', 'in', 'range' => array_keys(self::getRoles()), 'message' => self::t('Invalid {attribute} value.')],
            ['site', 'in', 'range' => array_keys(self::getSites()), 'message' => self::t('Invalid {attribute} value.')]
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert): bool
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            $this->query = Json::encode([
                'class' => md5(self::class),
                'owner' => $this->owner,
                'fullname' => $this->fullname,
                'email' => $this->email,
                'account_uuid' => $this->account_uuid,
            ]);
        }

        return $isValid;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        if ($this->_account === null) {
            $this->_account = Account::findOne(['uuid' => $this->account_uuid]);
        }

        return $this->_account;
    }

    /**
     * @param string $label
     * @return string
     */
    public static function t($label)
    {
        return \Yii::t('users', $label);
    }

    /**
     * @return array
     */
    public static function getOwners()
    {
        $owners = User::find()->orderBy(['CONCAT(`fname`,`lname`)' => SORT_ASC])->where([
            'uuid' => \users\models\User::find()
                ->distinct()
                ->select('{{%workflow}}.[[created_by]]')
                ->joinWith('workflow')
        ])->all();

        return ArrayHelper::map($owners, 'uuid', function (User $user) {
            return sprintf('%s <span class="text_muted">(%s)</span>', $user->getFullName(), $user->email);
        });
    }

    /**
     * @return array
     */
    public static function getRoles()
    {
        return AuthItem::getRoles();
    }

    /**
     * @return array
     */
    public static function getSites()
    {
        return Site::getList();
    }
}
