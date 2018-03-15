<?php

namespace forms\modules\admin\modules\fields\models;

use forms\models\Form;
use forms\modules\admin\modules\fields\validators\UniqueCodeValidator;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;

/**
 * Class Field
 *
 * @property string $form_uuid
 * @property Form $form
 *
 * @package forms\modules\admin\modules\fields\models
 */
class Field extends \fields\models\Field
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%forms_fields}}';
    }

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
        $rules[] = ['code', UniqueCodeValidator::class, 'message' => self::t('Field with code `{value}` is already exists.')];

        return $rules;
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['sg'] = [
            'class' => SluggableBehavior::class,
            'attribute' => 'label',
            'slugAttribute' => 'code',
            'ensureUnique' => true,
            'uniqueValidator' => ['class' => UniqueCodeValidator::class],
            'immutable' => true
        ];

        return $behaviors;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValidators()
    {
        return $this->hasMany(FieldValidator::class, ['field_uuid' => 'uuid'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldValues()
    {
        return $this->hasMany(FieldValue::class, ['field_uuid' => 'uuid'])->orderBy(['sort' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Form::class, ['uuid' => 'form_uuid']);
    }

    /**
     * @return Field
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

        $clone->code = null;

        return $clone;
    }
}