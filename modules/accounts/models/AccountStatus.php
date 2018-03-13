<?php
namespace accounts\models;

use app\components\behaviors\PrimaryKeyBehavior;
use partnership\models\Status;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class AccountSite
 *
 * @property string $uuid
 * @property string $account_uuid
 * @property string $status_uuid
 * @property \DateTime $issue_date
 * @property \DateTime $expire_date
 *
 * @property Status $status
 *
 * @package accounts\models
 */
class AccountStatus extends ActiveRecord
{
    /**
     * @var array
     */
    public $dates = [];
    /**
     * @var bool
     */
    private $_valid;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_statuses}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('accounts/statuses', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'status_uuid' => 'Status',
            'issue_date' => 'Issue date',
            'expire_date' => 'Expire date',
            'valid' => 'Valid',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'status_uuid' => 'Select one of available statuses.',
            'issue_date' => 'Date when status will be in use.',
            'expire_date' => 'Date until that status is in use.',
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
                ['discount_uuid', 'value'],
                'required',
                'message' => self::t('{attribute} is required.')
            ],
            [
                'status_uuid',
                'exist',
                'targetClass' => Status::class,
                'targetAttribute' => 'uuid',
                'message' => self::t('Invalid status selected.')
            ],
            [
                'status_uuid',
                'unique',
                'targetAttribute' => ['status_uuid', 'account_uuid'],
                'message' => self::t('This status already assigned.')
            ],
            [
                'dates',
                'each',
                'rule' => [
                    'date',
                    'format' => \Yii::$app->formatter->datetimeFormat,
                    'timestampAttribute' => 'dates',
                    'message' => self::t('Invalid date format.')
                ]
            ],
            [
                'dates',
                'validateDateRange'
            ],
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateDateRange($attribute)
    {
        $value = $this->$attribute;
        if (!empty($value['expire_date'])
            && ($value['issue_date'] > $value['expire_date'])
        ) {
            $this->addError($attribute . '[expire_date]', self::t('Expire date must be greater than issue date.'));
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            if (is_array($this->dates)) {
                $this->parseDates();
            }
        }

        return $isValid;
    }

    /**
     * @return bool
     */
    public function getValid()
    {
        if ($this->_valid === null) {
            $this->_valid = $this->isValid();
        }

        return $this->_valid;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $isValid = true;

        if ($this->issue_date) {
            $isValid = $isValid && (new \DateTime($this->issue_date))->getTimestamp() < time();
        }

        if ($this->expire_date) {
            $isValid = $isValid && (new \DateTime($this->expire_date))->getTimestamp() > time();
        }

        return $isValid;
    }

    /**
     * @param array $dates
     * @param null|string $format
     */
    public function formatDatesArray(array $dates, $format = null)
    {
        foreach ($dates as $attribute) {
            if ($this->$attribute) {
                $this->$attribute = \Yii::$app->formatter->asDatetime($this->$attribute, $format);
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['uuid' => 'status_uuid']);
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
     * @return AccountStatus
     */
    public function duplicate()
    {
        $copy = new self([
            'account_uuid' => $this->account_uuid,
            'issue_date' => $this->issue_date,
            'expire_date' => $this->expire_date,
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
            'sort' => false
        ]);
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params)
    {
        return self::find()->joinWith('status')->where($params);
    }

    /**
     * Using `FROM_UNIXTIME()` MySQL function to sets date attributes.
     */
    protected function parseDates()
    {
        foreach ($this->dates as $name => $date) {
            if (is_int($date)) {
                $expression = new Expression("FROM_UNIXTIME(:$name)", [":$name" => $date]);
                $this->setAttribute($name, $expression);
            }
            else {
                $this->setAttribute($name, null);
            }
        }
    }
}
