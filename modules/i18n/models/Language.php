<?php

namespace i18n\models;

use yii\db\ActiveRecord;
use yii\helpers\Inflector;

/**
 * Class Language
 * @property string $code
 * @property string $title
 * @property string $language
 * @property bool $default
 * @property int $sort
 *
 * @package i18n\models
 */
class Language extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%i18n_languages}}';
    }

    /**
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return \Yii::t('i18n', $message, $params);
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
    public static function getList(): array
    {
        return static::find()
            ->orderBy(['sort' => SORT_ASC, 'title' => SORT_ASC])
            ->indexBy('code')
            ->select('title')
            ->column();
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return (int) $this->default === 1;
    }

    /**
     * @param string $attribute
     * @return array
     */
    public static function getLangAttributeNames($attribute)
    {
        /* @var Language $languages */
        $languages = static::find()->all();
        $attributes = [$attribute];

        foreach ($languages as $language) {
            if ($language->code === \Yii::$app->language) {
                continue;
            }
            else {
                $attributes[] = static::getLangAttributeName($attribute, $language->code);
            }
        }

        return $attributes;
    }

    /**
     * @param string $attribute
     * @param string $language
     * @return string
     */
    public static function getLangAttributeName($attribute, $language)
    {
        return $attribute . '_' . Inflector::camel2id(Inflector::id2camel($language), '_');
    }
}