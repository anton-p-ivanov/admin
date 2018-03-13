<?php
namespace users\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\models\AuthAssignment;
use app\models\AuthItem;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class UserRole
 *
 * @property AuthItem $role
 *
 * @package users\models
 */
class UserRole extends AuthAssignment
{
    /**
     * @var array
     */
    public $valid_dates = [];

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
            'item_name' => 'Role',
            'valid_from_date' => 'Valid from',
            'valid_to_date' => 'Valid to',
            'role.description' => 'Role'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = [
            'item_name' => 'Select one of available roles.',
            'valid_from_date' => 'Specifies the date after that role is applied to user. If not value is set role is applied immediate after save.',
            'valid_to_date' => 'Specifies the date after that role will be withdrawn. If no value is set role is valid until deleted.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            // Required fields
            ['item_name', 'required', 'message' => self::t('{attribute} is required.')],
            // Date fields
            ['valid_dates', 'each', 'rule' => [
                'date',
                'format' => \Yii::$app->formatter->datetimeFormat,
                'timestampAttribute' => 'valid_dates',
                'message' => self::t('Invalid date format.')
            ]],
            ['valid_dates', 'validateDateRange'],
            // Unique fields
            [
                'item_name',
                'unique',
                'targetAttribute' => ['item_name', 'user_id'],
                'message' => self::t('This role already assigned.')
            ],
        ]);
    }

    /**
     * @param string $attribute
     */
    public function validateDateRange($attribute)
    {
        $value = $this->$attribute;
        if (!empty($value['valid_to_date'])
            && ($value['valid_from_date'] > $value['valid_to_date'])
        ) {
            $this->addError($attribute . '[valid_to_date]', self::t('Second date must be greater than first one.'));
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($isValid = parent::beforeSave($insert)) {
            $this->created_at = new Expression('NOW()');

            if (is_array($this->valid_dates)) {
                $this->parseValidDates();
            }
        }

        return $isValid;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(AuthItem::class, ['name' => 'item_name']);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $isValid = true;

        if ($this->valid_from_date) {
            $isValid = $isValid && (new \DateTime($this->valid_from_date))->getTimestamp() < time();
        }

        if ($this->valid_to_date) {
            $isValid = $isValid && (new \DateTime($this->valid_to_date))->getTimestamp() > time();
        }

        return $isValid;
    }

    /**
     * @param string $format
     */
    public function formatDatesArray($format = null)
    {
        foreach (['valid_from_date', 'valid_to_date'] as $attribute) {
            if ($this->$attribute) {
                $this->$attribute = \Yii::$app->formatter->asDatetime($this->$attribute, $format);
            }
        }
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
        return self::find()->where($params)->orderBy(['created_at' => SORT_ASC]);
    }

    /**
     * Using `FROM_UNIXTIME()` MySQL function to sets date attributes.
     */
    protected function parseValidDates()
    {
        foreach ($this->valid_dates as $name => $date) {
            if (is_int($date)) {
                $expression = new Expression("FROM_UNIXTIME(:$name)", [":$name" => $date]);
                $this->setAttribute($name, $expression);
            }
            else {
                $this->setAttribute($name, null);
            }
        }
    }

    /**
     * @return UserRole
     */
    public function duplicate()
    {
        $copy = new self([
            'user_id' => $this->user_id,
            'valid_from_date' => $this->valid_from_date,
            'valid_to_date' => $this->valid_to_date,
        ]);

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        return $copy;
    }
}
