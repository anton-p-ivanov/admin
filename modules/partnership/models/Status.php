<?php
namespace partnership\models;

use app\components\behaviors\PrimaryKeyBehavior;
use i18n\components\ActiveRecord;
use i18n\models\Language;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class Status
 *
 * @property string $uuid
 * @property string $title
 * @property string $code
 *
 * @package partnership\models
 */
class Status extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%partnership_statuses}}';
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return static::find()
            ->joinWith('translation')
            ->orderBy(['title' => SORT_ASC])
            ->select('title')
            ->indexBy('uuid')
            ->column();
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('partnership/statuses', $message, $params);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'title' => 'Title',
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
        $hints = [
            'title' => 'Up to 200 characters length.',
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
        $behaviors['pk'] = PrimaryKeyBehavior::className();
        $behaviors['sg'] = [
            'class' => SluggableBehavior::className(),
            'attribute' => 'title',
            'slugAttribute' => 'code',
            'ensureUnique' => true,
            'immutable' => true
        ];
        $behaviors['ml'] = ArrayHelper::merge($behaviors['ml'], [
            'langForeignKey' => 'status_uuid',
            'tableName' => '{{%partnership_statuses_i18n}}',
            'attributes' => ['title']
        ]);

        return $behaviors;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['title', 'required', 'message' => self::t('{attribute} is required.')],
            [['title', 'code'], 'string', 'max' => 200, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['code', 'unique', 'message' => self::t('Status with code `{value}` is already exists.')]
        ];
    }

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
            'asc' => ['{{%partnership_statuses_i18n}}.[[title]]' => SORT_ASC],
            'desc' => ['{{%partnership_statuses_i18n}}.[[title]]' => SORT_DESC],
        ];

        return $attributes;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected static function prepareSearchQuery()
    {
        return self::find()->multilingual()->joinWith('translation');
    }

    /**
     * @return Status
     */
    public function duplicate()
    {
        $copy = new self();

        foreach ($this->attributes as $name => $value) {
            if ($copy->isAttributeSafe($name)) {
                $copy->$name = $value;
            }
        }

        if (!$this->isRelationPopulated('translations')) {
            $this->populateRelation('translations', $this->getRelation('translations')->all());
        }

        foreach ($this->relatedRecords['translations'] as $translation) {
            if ($translation->lang === \Yii::$app->language) {
                $copy->{'title'} = $this->{'title'};
            }
            else {
                $attribute = Language::getLangAttributeName('title', $translation->lang);
                $copy->$attribute = $this->$attribute;
            }
        }

        return $copy;
    }
}
