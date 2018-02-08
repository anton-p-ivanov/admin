<?php
namespace i18n\components;

use i18n\models\Language;
use yii\db\ActiveQuery;

/**
 * Class MultilingualBehavior
 *
 * @property \yii\db\ActiveRecord $owner
 *
 * @package i18n\components
 */
class MultilingualBehavior extends \omgdef\multilingual\MultilingualBehavior
{
    /**
     * @var string
     */
    public $languageField = 'lang';
    /**
     * @var bool
     */
    public $abridge = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->languages) {
            $this->languages = Language::find()->select('code')->column();
        }
    }

    /**
     * Relation to model translation
     * @param string $language
     * @return ActiveQuery
     */
    public function getTranslation($language = null)
    {
        $language = $language ?: $this->getCurrentLanguage();
        return $this->owner->hasOne($this->langClassName, [$this->langForeignKey => $this->owner->primaryKey()[0]])
            ->where([sprintf('%s.[[%s]]', $this->tableName, $this->languageField) => $language]);
    }
}