<?php

namespace training\models;

use app\components\behaviors\DateRangeBehavior;
use app\components\behaviors\PrimaryKeyBehavior;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * Class Attempt
 *
 * @property string $uuid
 * @property string $test_uuid
 * @property string $user_uuid
 * @property bool $success
 * @property \DateTime $begin_date
 * @property \DateTime $end_date
 *
 * @property User $user
 *
 * @method formatDatesArray($format = null)
 *
 * @package training\models
 */
class Attempt extends ActiveRecord
{
    /**
     * @var array
     */
    public $dates;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%training_attempts}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('training/attempts', $message, $params);
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
        $behaviors['pk'] = PrimaryKeyBehavior::class;
        $behaviors['dates'] = DateRangeBehavior::class;

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'success' => 'Is successful',
            'user_uuid' => 'Student',
            'user.fullname' => 'Student',
            'user.email' => 'E-Mail',
            'user.account' => 'Account',
            'begin_date' => 'Begin date',
            'end_date' => 'End date',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'success' => 'Whether attempt is successful.',
            'user_uuid' => 'Select one of available users.',
            'begin_date' => 'Date and time when attempt was started.',
            'end_date' => 'Date and time when attempt was finished.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['user_uuid', 'required'],
            ['user_uuid', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'uuid'],
        ];
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
    public function isSuccessful()
    {
        return (int) $this->success === 1;
    }

    /**
     * @return Attempt
     */
    public function duplicate()
    {
        $clone = new self([
            'test_uuid' => $this->test_uuid,
            'begin_date' => $this->begin_date,
            'end_date' => $this->end_date,
        ]);

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        return $clone;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params)
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery($params),
            'pagination' => ['defaultPageSize' => 10],
            'sort' => [
                'defaultOrder' => ['begin_date' => SORT_DESC, 'end_date' => SORT_DESC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery($params)
    {
        return self::find()->joinWith(['user'])->where($params);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes(): array
    {
        $attributes = (new self())->attributes();
        $attributes['user.fullname'] = [
            'asc' => ['CONCAT({{%users}}.[[fname]], {{%users}}.[[lname]])' => SORT_ASC],
            'desc' => ['CONCAT({{%users}}.[[fname]], {{%users}}.[[lname]])' => SORT_DESC],
        ];

        return $attributes;
    }
}