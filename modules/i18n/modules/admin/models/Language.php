<?php

namespace i18n\modules\admin\models;

use yii\data\ActiveDataProvider;

/**
 * Class Language
 *
 * @package i18n\modules\admin\models
 */
class Language extends \i18n\models\Language
{
    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => ['title' => SORT_ASC],
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes()
    {
        return (new self())->attributes();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        return self::find();
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels =  [
            'title' => 'Title',
            'sort' => 'Sort',
            'default' => 'Default',
            'code' => 'Locale',
        ];

        return array_map('self::t', $labels);
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        $hints =  [
            'title' => 'Up to 200 characters length.',
            'sort' => 'Sorting index. Default is 100.',
            'default' => 'Whether to use this locale as default. Only one default locale can exist.',
            'code' => 'Locale in format of `ll-CC`, where `ll` is a two- or three-letter lowercase language code according to ISO-639 and `CC` is a two-letter country code according to ISO-3166.',
        ];

        return array_map('self::t', $hints);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'code'], 'required', 'message' => self::t('{attribute} is required.')],
            ['title', 'string', 'max' => 200, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['sort', 'integer', 'min' => 0, 'message' => self::t('{attribute} value must be greater than {min, number}.')],
            ['code', 'unique', 'message' => self::t('{attribute} value already in use.')],
            ['code', 'match', 'pattern' => '/^\w{2,3}\-\w{2}$/i', 'message' => self::t('{attribute} value contains invalid characters.')],
            ['sort', 'default', 'value' => 100],
            ['default', 'boolean'],
            ['default', 'validateDefault'],
            ['default', 'default', 'value' => 0]
        ];
    }

    /**
     * @param $attribute
     */
    public function validateDefault($attribute)
    {
        $default = self::findOne(['default' => 1]);

        if ($default && (int) $this->$attribute === 0 && $default->code === $this->code) {
            $this->addError($attribute, 'Could not change `default` attribute on default language.');
        }
    }

    /**
     * @return Language
     */
    public function duplicate()
    {
        $clone = new self();

        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeSafe($attribute)) {
                $clone->$attribute = $value;
            }
        }

        return $clone;
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
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        if ((int)self::find()->where(['default' => 1])->count() === 0) {
            self::find()->one()->updateAttributes(['default' => 1]);
        }
    }
}