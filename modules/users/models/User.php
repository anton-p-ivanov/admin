<?php
namespace users\models;

use accounts\models\Account;
use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Workflow;
use yii\data\ActiveDataProvider;

/**
 * Class User
 *
 * @property Workflow $workflow
 * @property Account[] $accounts
 *
 * @package users\models
 */
class User extends \app\models\User
{
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
        $behaviors[] = PrimaryKeyBehavior::className();
        $behaviors[] = WorkflowBehavior::className();

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = [
            'email' => 'E-Mail',
            'fname' => 'First name',
            'lname' => 'Last name',
            'sname' => 'Second name',
            'access_date' => 'Access date',
            'workflow.modified_date' => 'Modified',
            'fullname' => 'Fullname',
            'accounts' => 'Accounts'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'email' => 'Must be valid E-Mail address.',
            'fname' => 'Up to 100 characters length.',
            'lname' => 'Up to 100 characters length.',
            'sname' => 'Up to 100 characters length.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'fname', 'lname'], 'required', 'message' => self::t('{attribute} is required.')],
            ['email', 'email', 'message' => self::t('{attribute} must be a valid E-Mail address.')],
            ['email', 'unique', 'message' => self::t('{value} is already exists.')],
            [['fname', 'lname', 'sname'], 'string', 'max' => 100, 'message' => self::t('Maximum {max, number} characters allowed.')],
        ];
    }

    /**
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields['fullname'] = 'fullname';

        return $fields;
    }

    /**
     * @param \users\models\UserSettings|\app\models\UserSettings $settings
     * @return ActiveDataProvider
     */
    public static function search($settings)
    {
        $defaultOrder = ['fullname' => SORT_ASC];
        if ($settings) {
            $defaultOrder = [$settings->sortBy => $settings->sortOrder];
        }

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
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
        $attributes = (new self())->attributes();
        $attributes['fullname'] = [
            'asc' => ['CONCAT(`fname`,`lname`)' => SORT_ASC],
            'desc' => ['CONCAT(`fname`,`lname`)' => SORT_DESC],
        ];
        $attributes['workflow.modified_date'] = [
            'asc' => ['{{%workflow}}.[[modified_date]]' => SORT_ASC],
            'desc' => ['{{%workflow}}.[[modified_date]]' => SORT_DESC],
        ];

        return $attributes;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        /* @var \yii\db\ActiveQuery $query */
        $query = self::find()->joinWith('workflow');

        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['uuid' => 'account_uuid'])
            ->viaTable(UserAccount::tableName(), ['user_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPasswords()
    {
        return $this->hasMany(UserPassword::className(), ['user_uuid' => 'uuid'])->orderBy(['created_date' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(UserRole::className(), ['user_id' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSites()
    {
        return $this->hasMany(UserSite::className(), ['user_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::className(), ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return User
     */
    public function duplicate()
    {
        $copy = new self();

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        return $copy;
    }
}
