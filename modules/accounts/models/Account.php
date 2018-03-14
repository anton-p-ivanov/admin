<?php
namespace accounts\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use app\models\Site;
use app\models\Workflow;
use partnership\models\Status;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Account
 *
 * @property string $uuid
 * @property string $title
 * @property string $description
 * @property string $details
 * @property string $email
 * @property string $web
 * @property string $phone
 * @property bool $active
 * @property int $sort
 * @property string $parent_uuid
 * @property string $workflow_uuid
 *
 * @property AccountCode $accountCode
 * @property Workflow $workflow
 *
 * @package accounts\models
 */
class Account extends ActiveRecord
{
    /**
     * @var bool
     */
    public $notify = false;
    /**
     * @var Site[]
     */
    private $_sites;
    /**
     * @var Type[]
     */
    private $_types;
    /**
     * @var Status[]
     */
    private $_statuses;
    /**
     * @var array
     */
    private $_delete;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('accounts', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'title' => 'Title',
            'description' => 'Description',
            'details' => 'Detailed info',
            'email' => 'Contact E-Mail',
            'web' => 'Public Web-site',
            'phone' => 'Contact phone',
            'active' => 'Is active',
            'notify' => 'Updates notification',
            'sort' => 'Sort',
            'parent_uuid' => 'Head company',
            'types' => 'Account types',
            'workflow.modified_date' => 'Modified',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'title' => 'Up to 250 characters length.',
            'email' => 'Valid E-Mail address to contact.',
            'web' => 'Company corporate web-site URL.',
            'phone' => 'Valid phone number.',
            'active' => 'Whether account is active.',
            'notify' => 'Send a change notice to account company.',
            'parent_uuid' => 'Select head company if available.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'email', 'web'], 'required', 'message' => self::t('{attribute} is required.')],
            [['title', 'phone'], 'string', 'max' => 255, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['email', 'email', 'message' => self::t('{attribute} must be a valid E-Mail address.')],
            ['email', 'unique', 'message' => self::t('{value} is already exists.')],
            ['web', 'url', 'defaultScheme' => 'http', 'message' => self::t('{attribute} must be a valid Url.')],
            [['active', 'notify'], 'boolean'],
            ['description', 'safe'],
            ['sort', 'integer', 'min' => 0, 'message' => self::t('{attribute} value must be greater than {min, number}.')],
            ['parent_uuid', 'exist', 'targetClass' => self::class, 'targetAttribute' => 'uuid', 'message' => self::t('Invalid value.')],
            ['types', 'exist', 'targetClass' => Type::class, 'targetAttribute' => 'uuid', 'allowArray' => true, 'message' => self::t('Invalid value.')],
            ['sites', 'exist', 'targetClass' => Site::class, 'targetAttribute' => 'uuid', 'allowArray' => true, 'message' => self::t('Invalid value.')],
            ['statuses', 'exist', 'targetClass' => Status::class, 'targetAttribute' => 'uuid', 'allowArray' => true, 'message' => self::t('Invalid value.')],
            ['parent_uuid', 'validateParent'],
            // Default values
            [['details', 'description'], 'default', 'value' => ''],
            ['parent_uuid', 'default', 'value' => null],
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateParent($attribute)
    {
        if ($this->$attribute === $this->uuid) {
            $this->addError($attribute, self::t('Invalid head company.'));
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
        $behaviors[] = WorkflowBehavior::class;

        return $behaviors;
    }

    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        $defaultOrder = ['title' => SORT_ASC];

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
    public function getAccountCode()
    {
        return $this->hasOne(AccountCode::class, ['account_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountSites()
    {
        return $this->hasMany(AccountSite::class, ['account_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountStatuses()
    {
        return $this->hasMany(AccountStatus::class, ['account_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountTypes()
    {
        return $this->hasMany(AccountType::class, ['account_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountContacts()
    {
        return $this->hasMany(AccountContact::class, ['account_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountManagers()
    {
        return $this->hasMany(AccountManager::class, ['account_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['uuid' => 'parent_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSitesRelation()
    {
        return $this->hasMany(Site::class, ['uuid' => 'site_uuid'])->via('accountSites');
    }

    /**
     * @return array
     */
    public function getSites()
    {
        if ($this->_sites === null) {
            $this->_sites = $this->getSitesRelation()->all();
        }

        return $this->_sites;
    }

    /**
     * @param array $sites
     */
    public function setSites($sites)
    {
        $this->_sites = is_array($sites) ? array_unique($sites) : [];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusesRelation()
    {
        return $this->hasMany(Status::class, ['uuid' => 'status_uuid'])->via('accountStatuses');
    }

    /**
     * @return Status[]
     */
    public function getStatuses()
    {
        if ($this->_statuses === null) {
            $this->_statuses = $this->getStatusesRelation()->all();
        }

        return $this->_statuses;
    }

    /**
     * @param array $statuses
     */
    public function setStatuses($statuses)
    {
        $this->_statuses = is_array($statuses) ? array_unique($statuses) : [];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypesRelation()
    {
        return $this->hasMany(Type::class, ['uuid' => 'type_uuid'])->via('accountTypes');
    }

    /**
     * @return Type[]
     */
    public function getTypes()
    {
        if ($this->_types === null) {
            $this->_types = $this->getTypesRelation()->all();
        }

        return $this->_types;
    }

    /**
     * @param array $types
     */
    public function setTypes($types)
    {
        $this->_types = is_array($types) ? array_unique($types) : [];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkflow()
    {
        return $this->hasOne(Workflow::class, ['uuid' => 'workflow_uuid']);
    }

    /**
     * @return Account
     */
    public function duplicate()
    {
        $copy = new self();

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        $copy->setSites(ArrayHelper::getColumn($this->getSites(), 'uuid'));
        $copy->setTypes(ArrayHelper::getColumn($this->getTypes(), 'uuid'));

        return $copy;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (int) $this->active === 1;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $links = ['sites', 'types', 'statuses'];

        foreach ($links as $link) {
            $this->linkAll($link . 'Relation', $this->{'_' . $link});
        }
//
//        if ($insert || $this->change_code) {
//            $this->changeRegistrationCode($insert);
//        }
//
//        if ($this->notify) {
//            // Sends message to account email
//            $this->sendMessage($insert ? 'ACCOUNT_CREATED' : 'ACCOUNT_UPDATED');
//        }
    }

    /**
     * @param string $name Relation name
     * @param array $models primary keys array
     */
    protected function linkAll($name, $models)
    {
        $this->unlinkAll($name, true);

        /* @var \yii\db\ActiveRecord $modelClass */
        $modelClass = $this->getRelation($name)->modelClass;

        foreach ($modelClass::findAll($models) as $model) {
            $this->link($name, $model);
        }
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $isValid = parent::beforeDelete();

        if ($isValid) {
            $this->_delete['addresses'] = AccountAddress::find()
                ->where(['account_uuid' => $this->uuid])
                ->select('address_uuid')
                ->column();
        }

        return $isValid;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        if ($this->_delete) {
            foreach ($this->_delete as $type => $items) {
                switch ($type) {
                    case 'addresses':
                        Address::deleteAll(['uuid' => $items]);
                        break;
                    default:
                        break;
                }
            }
        }
    }
}
