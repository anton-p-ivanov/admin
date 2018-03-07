<?php

namespace catalogs\modules\admin\modules\fields\models;

use catalogs\modules\admin\models\Catalog;

/**
 * Class Field
 *
 * @property string $catalog_uuid
 * @property string $group_uuid
 *
 * @property FieldValidator[] $fieldValidators
 * @property FieldValue[] $fieldValues
 * @property Catalog $catalog
 * @property Group $group
 *
 * @package catalogs\modules\admin\modules\fields\models
 */
class Field extends \fields\models\Field
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%catalogs_fields}}';
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = parent::attributeLabels();

        $labels['group_uuid'] = self::t('Group');

        return $labels;
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('catalogs/fields', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        $hints = parent::attributeHints();

        $hints['group_uuid'] = self::t('Select one of available groups.');

        return $hints;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules[] = [
            'group_uuid', 'exist', 'targetClass' => Group::class, 'targetAttribute' => 'uuid'
        ];

        $rules[] = [
            'group_uuid', 'default', 'value' => null
        ];

        return $rules;
    }

    /**
     * @param $attribute
     */
    public function validateValues($attribute)
    {
        if (!$this->hasValues() && $this->multiple) {
            $this->addError($attribute, self::t('Type `{type}` can not be assigned to `multiple` fields.', ['type' => self::getTypes()[$this->type]]));
        }
    }

    /**
     * @return Field|bool
     */
    public function duplicate()
    {
        $clone = new self();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        $clone->code = null;
        $clone->catalog_uuid = $this->catalog_uuid;

        return $clone;
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
    public function getCatalog()
    {
        return $this->hasOne(Catalog::class, ['uuid' => 'catalog_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['uuid' => 'group_uuid']);
    }

    /**
     * @return Field[]
     */
    public static function getList()
    {
        return Field::find()
            ->where(['active' => true])
            ->orderBy(['sort' => SORT_ASC])
            ->indexBy('uuid')
            ->all();
    }
}