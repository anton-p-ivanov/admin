<?php

namespace accounts\modules\fields\models;

use fields\models\FieldRelation;
use accounts\models\AccountData;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\validators\UniqueValidator;

/**
 * Class Field
 *
 * @package accounts\modules\fields\models
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

        if ($settings instanceof FieldSettings) {
            $defaultOrder = [$settings->sortBy => $settings->sortOrder];
        }

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

    /**
     * @param bool $deepCopy
     * @return Field|bool
     */
    public function duplicate($deepCopy = false)
    {
        $clone = new self([
            'sort' => 100
        ]);

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        $appendixLength = 7;
        if (mb_strlen($clone->code) > (50 - $appendixLength)) {
            $clone->code = mb_substr($clone->code, 0, (50 - $appendixLength));
        }

        $clone->code .= '_' . self::generateCodeAppendix($appendixLength - 1);
        $clone->type = self::FIELD_TYPE_DEFAULT;

        if ($clone->save()) {

            $clone->updateAttributes([
                'type' => $this->type,
                'multiple' => $this->multiple
            ]);

            if ($deepCopy) {
                foreach (['fieldValues', 'fieldValidators'] as $relation) {
                    /* @var FieldRelation $values */
                    $values = $this->$relation;
                    foreach ($values as $value) {
                        $value->field_uuid = $clone->uuid;
                        $value->duplicate()->save();
                    }
                }
            }

            return $clone;
        }

        return false;
    }
}