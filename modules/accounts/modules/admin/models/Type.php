<?php
namespace accounts\modules\admin\models;

use app\components\traits\ActiveSearch;
use i18n\models\Language;

/**
 * Class Type
 *
 * @package accounts\modules\admin\models
 */
class Type extends \accounts\models\Type
{
    use ActiveSearch;

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'title' => 'Title',
            'sort' => 'Sort',
            'default' => 'Default',
            'accounts' => 'Accounts',
        ];

        $labels = array_map('self::t', $labels);

        $behavior = \Yii::createObject($this->behaviors()['ml']);

        foreach ($behavior->attributes as $attribute) {
            foreach ($behavior->languages as $language) {
                $labels[Language::getLangAttributeName($attribute, $language)] = $labels[$attribute] . ' (' . $language .')';
            }
        }

        return $labels;
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints = [
            'title' => 'Up to 200 characters length.',
            'sort' => 'Sorting index. Default is 100.',
            'default' => 'Default type will assigned to new accounts automatically.'
        ];

        $behavior = \Yii::createObject($this->behaviors()['ml']);

        foreach ($behavior->attributes as $attribute) {
            foreach ($behavior->languages as $language) {
                $hints[Language::getLangAttributeName($attribute, $language)] = $hints[$attribute];
            }
        }

        return array_map('self::t', $hints);
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
    public function rules()
    {
        return [
            ['title', 'required', 'message' => self::t('{attribute} is required.')],
            ['title', 'string', 'max' => 200, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['sort', 'integer', 'min' => 0, 'message' => self::t('{attribute} value must be greater or equal {min, number}.')],
            ['default', 'boolean']
        ];
    }

    /**
     * @return array
     */
    protected static function getSortAttributes()
    {
        $attributes = (new self())->attributes();
        $attributes['title'] = [
            'asc' => ['{{%accounts_types_i18n}}.[[title]]' => SORT_ASC],
            'desc' => ['{{%accounts_types_i18n}}.[[title]]' => SORT_DESC],
        ];

        return $attributes;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        return self::find()->joinWith('translation');
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            if ($this->isDefault()) {
                self::updateAll(['default' => 0]);
            }
        }

        return $isValid;
    }

    /**
     * @return Type
     */
    public function duplicate()
    {
        $copy = new self();

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        $copy->default = 0;

        if (!$this->isRelationPopulated('translations')) {
            $this->populateRelation('translations', $this->getRelation('translations')->all());
        }

        foreach ($this->relatedRecords['translations'] as $translation) {
            if ($translation->lang === \Yii::$app->language) {
                $copy->title = $this->title;
            }
            else {
                $attribute = Language::getLangAttributeName('title', $translation->lang);
                $copy->$attribute = $this->$attribute;
            }
        }

        return $copy;
    }
}
