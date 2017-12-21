<?php
namespace i18n\components;

use i18n\models\Language;

/**
 * Class MultilingualBehavior
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
}