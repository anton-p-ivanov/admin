<?php
namespace sales\modules\discounts\models;

use app\components\behaviors\PrimaryKeyBehavior;
use sales\models\Discount;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class StatusDiscount
 *
 * @property string $uuid
 * @property string $status_uuid
 * @property string $discount_uuid
 * @property double $value
 * @property \DateTime $issue_date
 * @property \DateTime $expire_date
 *
 * @property ActiveRecord $status
 * @property Discount $discount
 *
 * @package sales\modules\discounts\models
 */
class StatusDiscount extends ActiveRecord
{
    /**
     * @var ActiveRecord
     */
    public static $statusModel;
    /**
     * @var array
     */
    public $dates = [];
    /**
     * @var bool
     */
    private $_valid;

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('sales/discounts', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'discount_uuid' => 'Discount',
            'issue_date' => 'Issue date',
            'expire_date' => 'Expire date',
            'valid' => 'Valid',
            'value' => 'Value',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'discount_uuid' => 'Select one of available discounts.',
            'value' => 'Discount value in percent. Use default value of selected discount if empty.',
            'issue_date' => 'Date when discount will be in use.',
            'expire_date' => 'Date until that discount is in use.',
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
                'discount_uuid',
                'exist',
                'targetClass' => Discount::class,
                'targetAttribute' => 'uuid',
                'message' => self::t('Invalid discount.')
            ],
            [
                'discount_uuid',
                'unique',
                'targetAttribute' => ['discount_uuid', 'status_uuid'],
                'message' => self::t('This discount already assigned.')
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
            ['dates', 'validateDateRange'],
            ['value', 'default', 'value' => 0],
            ['value', 'double', 'min' => 0, 'max' => 100]
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

            $this->value = (double) $this->value / 100;
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
        return $this->hasOne(static::$statusModel, ['uuid' => 'status_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscount()
    {
        return $this->hasOne(Discount::class, ['uuid' => 'discount_uuid']);
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
     * @return StatusDiscount
     */
    public function duplicate()
    {
        $copy = new static([
            'status_uuid' => $this->status_uuid,
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
     * @param string $status_uuid
     * @return ActiveDataProvider
     */
    public static function search($status_uuid)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($status_uuid),
            'sort' => false
        ]);
    }

    /**
     * @param string $status_uuid
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($status_uuid)
    {
        return self::find()->joinWith('discount')->where(['status_uuid' => $status_uuid]);
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
