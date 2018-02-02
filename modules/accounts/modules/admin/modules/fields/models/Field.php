<?php

namespace accounts\modules\admin\modules\fields\models;

use accounts\models\AccountData;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\validators\UniqueValidator;

/**
 * Class Field
 *
 * @package accounts\modules\admin\modules\fields\models
 */
class Field extends \fields\models\Field
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%accounts_fields}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValidators()
    {
        return $this->hasMany(FieldValidator::className(), ['field_uuid' => 'uuid'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValues()
    {
        return $this->hasMany(FieldValue::className(), ['field_uuid' => 'uuid'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @param array $settings
     * @return ActiveDataProvider
     */
    public static function search($settings = [])
    {
        $defaultOrder = ['label' => SORT_ASC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery([]),
            'sort' => [
                'defaultOrder' => $defaultOrder,
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules[] = [
            'code',
            UniqueValidator::className(),
            'message' => self::t('Field with code `{value}` is already exists.')
        ];

        return $rules;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // If field code was changed update all form results
        if (!$insert && array_key_exists('code', $changedAttributes)) {
            /* @var AccountData[] $results */
            $results = AccountData::find()->all();
            foreach ($results as $result) {
                $data = Json::decode($result->data);
                if (isset($data[$changedAttributes['code']])) {
                    $data[$this->code] = $data[$changedAttributes['code']];
                    unset($data[$changedAttributes['code']]);
                }

                $result->updateAttributes(['data' => Json::encode($data)]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        /* @var AccountData[] $results */
        $results = AccountData::find()->all();
        foreach ($results as $result) {
            $data = Json::decode($result->data);
            if (isset($data[$this->code])) {
                unset($data[$this->code]);
            }

            $result->updateAttributes(['data' => Json::encode($data)]);
        }
    }
}