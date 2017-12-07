<?php

namespace forms\modules\fields\models;

use fields\models\FieldRelation;
use forms\models\Form;
use forms\modules\fields\validators\UniqueCodeValidator;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * Class Field
 *
 * @property string $form_uuid
 * @property Form $form
 *
 * @package forms\modules\fields\models
 */
class Field extends \fields\models\Field
{
    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public static function search($params = [])
    {
        return new ActiveDataProvider([
            'query' => static::prepareSearchQuery($params),
            'pagination' => ['defaultPageSize' => 10],
            'sort' => [
                'defaultOrder' => ['workflow.modified_date' => SORT_DESC],
                'attributes' => static::getSortAttributes()
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
            UniqueCodeValidator::className(),
            'message' => self::t('Field with code `{value}` is already exists.')
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['sg'] = [
            'class' => SluggableBehavior::className(),
            'attribute' => 'label',
            'slugAttribute' => 'code',
            'ensureUnique' => true,
            'uniqueValidator' => ['class' => UniqueCodeValidator::className()],
            'immutable' => true
        ];

        return $behaviors;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // If field code was changed update all form results
        if (array_key_exists('code', $changedAttributes)) {
            foreach ($this->form->results as $result) {
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
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::className(), ['uuid' => 'form_uuid']);
    }

    /**
     * @return Field|bool
     */
    public function duplicate()
    {
        $clone = new self([
            'form_uuid' => $this->form_uuid,
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

            foreach (['fieldValues', 'fieldValidators'] as $relation) {
                /* @var FieldRelation $values */
                $values = $this->$relation;
                foreach ($values as $value) {
                    $value->field_uuid = $clone->uuid;
                    $value->duplicate()->save();
                }
            }

            return $clone;
        }

        return false;
    }
}