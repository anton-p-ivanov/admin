<?php

namespace catalogs\modules\admin\models;

use app\components\behaviors\PrimaryKeyBehavior;
use app\components\behaviors\WorkflowBehavior;
use catalogs\modules\admin\modules\fields\models\Field;
use catalogs\modules\admin\modules\fields\models\Group;
use i18n\models\Language;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class Catalog
 *
 * @property Group[] $groups
 * @property Field[] $fields
 *
 * @package catalogs\modules\admin\models
 */
class Catalog extends \catalogs\models\Catalog
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
            'asc' => ['{{%catalogs_i18n}}.[[title]]' => SORT_ASC],
            'desc' => ['{{%catalogs_i18n}}.[[title]]' => SORT_DESC],
        ];
        $attributes['type.title'] = [
            'asc' => ['{{%catalogs_types_i18n}}.[[title]]' => SORT_ASC],
            'desc' => ['{{%catalogs_types_i18n}}.[[title]]' => SORT_DESC],
        ];

        return $attributes;
    }

    /**
     * @return ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        /* @var ActiveQuery $query */
        $query = self::find()->joinWith(['translation', 'type' => function (ActiveQuery $query) {
            $query->joinWith('translation');
        }]);

        return $query;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pk'] = PrimaryKeyBehavior::className();
        $behaviors['wf'] = WorkflowBehavior::className();
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
     * @return array
     */
    public function attributeLabels()
    {
        $labels =  [
            'title' => 'Title',
            'description' => 'Description',
            'sort' => 'Sort',
            'code' => 'Code',
            'type_uuid' => 'Type',
            'type.title' => 'Type',
            'active' => 'Is active',
            'trade' => 'Use in trade module',
            'index' => 'Index elements',
            'fields' => 'Fields',
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
            'description' => 'Short catalog description.',
            'sort' => 'Sorting index. Default is 100.',
            'code' => 'Unique symbolic code.',
            'active' => 'Whether catalog is active and can be used in public web sites.',
            'trade' => 'Prices can be assigned to this catalogs` elements.',
            'index' => 'Catalogs` elements will be indexed by search engine.',
            'type_uuid' => 'Select one of available catalog`s types.'
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
            [['title', 'code', 'type_uuid'], 'required', 'message' => self::t('{attribute} is required.')],
            [['title', 'code'], 'string', 'max' => 255, 'tooLong' => self::t('Maximum {max, number} characters allowed.')],
            [['active', 'trade', 'index'], 'boolean'],
            ['active', 'default', 'value' => 1],
            [['trade', 'index'], 'default', 'value' => 0],
            ['type_uuid', 'exist', 'targetClass' => Type::className(), 'targetAttribute' => 'uuid'],
            [
                'sort',
                'integer',
                'min' => 0,
                'message' => self::t('{attribute} value must be an integer.'),
                'tooSmall' => self::t('{attribute} value must be greater or equal {min, number}.')
            ],
            ['sort', 'default', 'value' => 100],
            ['description', 'safe'],
            ['code', 'unique', 'message' => self::t('{attribute} value is already in use.')],
            ['code', 'match', 'pattern' => '/^[\w\d\-\_]+$/i'],
        ];
    }

    /**
     * @return Catalog
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
                $clone->{'description'} = $this->{'description'};
            }
            else {
                foreach (['title', 'description'] as $item) {
                    $attribute = Language::getLangAttributeName($item, $translation->lang);
                    $clone->$attribute = $this->$attribute;
                }
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
            $this->code = mb_strtoupper($this->code);
        }

        return $isValid;
    }

    /**
     * @return ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['catalog_uuid' => 'uuid']);
    }

    /**
     * @return ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['catalog_uuid' => 'uuid']);
    }
}
