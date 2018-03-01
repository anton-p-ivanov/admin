<?php
namespace users\modules\admin\models;

use app\models\AuthItem;
use i18n\models\Language;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * Class Role
 *
 * @package users\modules\admin\models
 */
class Role extends AuthItem
{
    /**
     * @param $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('users/roles', $message, $params);
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
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['ts'] = [
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()')
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        $labels = [
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created',
            'updated_at' => 'Modified',
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
    public function attributeHints(): array
    {
        $hints = [
            'name' => 'Up to 100 characters length.',
            'description' => 'Role short description.',
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
    public function rules(): array
    {
        return [
            [['name', 'description'], 'required', 'message' => self::t('{attribute} is required.')],
            ['name', 'string', 'max' => 100, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['description', 'string', 'max' => 200, 'message' => self::t('Maximum {max, number} characters allowed.')],
            ['name', 'match', 'pattern' => '/^[\w\-]+$/i', 'message' => self::t('Invalid characters found.')],
            ['name', 'unique'],
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public static function search()
    {
        return new ActiveDataProvider([
            'query' => self::prepareSearchQuery(),
            'sort' => [
                'defaultOrder' => ['description' => SORT_ASC],
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
        $attributes['description'] = [
            'asc' => ['{{%auth_items_i18n}}.[[description]]' => SORT_ASC],
            'desc' => ['{{%auth_items_i18n}}.[[description]]' => SORT_DESC],
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
     * @return Role
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
                $copy->description = $this->description;
            }
            else {
                $attribute = Language::getLangAttributeName('description', $translation->lang);
                $copy->$attribute = $this->$attribute;
            }
        }

        return $copy;
    }
}
