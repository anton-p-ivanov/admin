<?php
namespace app\models;

use app\components\behaviors\PrimaryKeyBehavior;
use yii\db\ActiveRecord;

/**
 * Class Address
 *
 * @property string $uuid
 * @property string $type_uuid
 * @property string $country_code
 * @property string $region
 * @property string $district
 * @property string $city
 * @property string $zip
 * @property string $address
 *
 * @property AddressCountry $country
 * @property AddressType $type
 *
 * @package app\models
 */
class Address extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%addresses}}';
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
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('addresses', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'type_uuid' => 'Type',
            'country_code' => 'Country',
            'region' => 'Region',
            'district' => 'District',
            'city' => 'City',
            'zip' => 'ZIP',
            'address' => 'Address'
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'type_uuid' => 'Select available address type.',
            'country_code' => 'Type and select country.',
            'region' => 'Maximum 255 characters allowed.',
            'district' => 'Maximum 255 characters allowed.',
            'city' => 'Maximum 255 characters allowed.',
            'zip' => 'Postal code.',
            'address' => 'Street, apartment, etc.'
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['type_uuid', 'country_code', 'city', 'zip', 'address'], 'required', 'message' => self::t('{attribute} is required.')],
            ['country_code', 'exist', 'targetClass' => AddressCountry::class, 'targetAttribute' => 'code'],
            [['region', 'district', 'city', 'address'], 'string', 'max' => 255, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['zip', 'string', 'max' => 50, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['type_uuid', 'exist', 'targetClass' => AddressType::class, 'targetAttribute' => 'uuid'],
        ];
    }

    /**
     * @return Address
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(AddressCountry::class, ['code' => 'country_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(AddressType::class, ['uuid' => 'type_uuid']);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $data = [
            $this->country ? $this->country->title : $this->country_code,
            $this->zip,
            $this->region,
            $this->district,
            $this->city,
            $this->address
        ];

        return implode(', ', array_filter($data, 'strlen'));
    }
}
