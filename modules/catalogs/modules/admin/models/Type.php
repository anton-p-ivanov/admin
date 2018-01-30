<?php

namespace catalogs\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use i18n\models\Language;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;

/**
 * Class Type
 *
 * @package catalogs\modules\admin\models
 */
class Type extends \catalogs\models\Type
{

    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        $defaultOrder = ['title' => SORT_ASC];

        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => $defaultOrder,
                'attributes' => self::getSortAttributes()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected static function getSortAttributes()
    {
        $attributes = (new self())->attributes();
        $attributes['title'] = [
            'asc' => ['{{%catalogs_types_i18n}}.[[title]]' => SORT_ASC],
            'desc' => ['{{%catalogs_types_i18n}}.[[title]]' => SORT_DESC],
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
     * @return array
     */
    public function attributeLabels()
    {
        $labels =  [
            'title' => 'Title',
            'sort' => 'Sort',
            'catalogs' => 'Catalogs',
            'code' => 'Code',
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
        $hints =  [
            'title' => 'Up to 200 characters length.',
            'sort' => 'Sorting index. Default is 100.',
            'code' => 'Unique symbolic code.',
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
    public function rules()
    {
        return [
            ['title', 'required', 'message' => self::t('{attribute} is required.')],
            ['title', 'string', 'max' => 200, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['sort', 'integer', 'min' => 0, 'message' => self::t('{attribute} value must be greater than {min, number}.')],
            ['code', 'unique', 'message' => self::t('{attribute} value already in use.')],
            ['code', 'match', 'pattern' => '/^[\w\d\-]*$/i', 'message' => self::t('{attribute} value contains invalid characters.')],
            ['sort', 'default', 'value' => 100],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::className();
        $behaviors['sg'] = [
            'class' => SluggableBehavior::className(),
            'attribute' => 'title',
            'slugAttribute' => 'code',
            'ensureUnique' => true,
            'immutable' => true
        ];

        return $behaviors;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $isValid = parent::beforeSave($insert);

        if ($isValid) {
            $this->code = mb_strtoupper($this->code);
        }

        return $isValid;
    }

    /**
     * @return Type
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

        if (!$this->isRelationPopulated('translations')) {
            $this->populateRelation('translations', $this->getRelation('translations')->all());
        }

        foreach ($this->relatedRecords['translations'] as $translation) {
            if ($translation->lang === \Yii::$app->language) {
                $clone->{'title'} = $this->{'title'};
            }
            else {
                $attribute = Language::getLangAttributeName('title', $translation->lang);
                $clone->$attribute = $this->$attribute;
            }
        }

        return $clone;
    }
}
