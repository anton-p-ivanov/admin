<?php
namespace users\models;

use accounts\models\Account;
use app\components\behaviors\DateRangeBehavior;
use app\components\behaviors\PrimaryKeyBehavior;
use app\models\Site;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class UserSite
 *
 * @property string $uuid
 * @property string $user_uuid
 * @property string $site_uuid
 * @property bool $active
 * @property \DateTime $active_from_date
 * @property \DateTime $active_to_date
 * @property \DateTime $login_date
 * @property \DateTime $activity_date
 *
 * @property Account $account
 * @property Site $site
 *
 * @method formatDatesArray($format = null)
 *
 * @package users\models
 */
class UserSite extends ActiveRecord
{
    /**
     * @var array
     */
    public $active_dates = [];

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users_sites}}';
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
        $behaviors[] = [
            'class' => DateRangeBehavior::class,
            'attribute' => 'active_dates',
            'targetAttributes' => ['active_from_date', 'active_to_date']
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = [
            'site_uuid' => 'Site',
            'active_from_date' => 'Active from',
            'active_to_date' => 'Active to',
            'site.title' => 'Site',
            'login_date' => 'Login date'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'site_uuid' => 'Select one of available sites.',
            'active_from_date' => 'Specifies the date after that access to site is granted. If not value is set access to site is granted immediate after save.',
            'active_to_date' => 'Specifies the date after that access to site will be blocked. If no value is set access to site is granted until deleted.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['site_uuid', 'required', 'message' => self::t('{attribute} is required.')],
            [
                'site_uuid',
                'exist',
                'targetClass' => Site::class,
                'targetAttribute' => 'uuid',
                'message' => self::t('Invalid site.')
            ],
            ['active', 'boolean'],
            // Unique fields
            [
                'site_uuid',
                'unique',
                'targetAttribute' => ['site_uuid', 'user_uuid'],
                'message' => self::t('This site already assigned.')
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::class, ['uuid' => 'site_uuid']);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $isActive = $this->active === 1;

        if ($this->active_from_date) {
            $isActive = $isActive && (new \DateTime($this->active_from_date))->getTimestamp() < time();
        }

        if ($this->active_to_date) {
            $isActive = $isActive && (new \DateTime($this->active_to_date))->getTimestamp() > time();
        }

        return $isActive;
    }

    /**
     * @return UserSite
     */
    public function duplicate()
    {
        $copy = new self([
            'user_uuid' => $this->user_uuid,
            'active_from_date' => $this->active_from_date,
            'active_to_date' => $this->active_to_date,
        ]);

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        return $copy;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($params),
            'pagination' => ['defaultPageSize' => 5],
            'sort' => false
        ]);
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params)
    {
        return self::find()->where($params)->joinWith('site')
            ->orderBy(['{{%sites}}.[[title]]' => SORT_ASC]);
    }
}
