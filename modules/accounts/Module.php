<?php

namespace accounts;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package accounts
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'dashboard';
    /**
     * @var string
     */
    public static $title = 'Accounts';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // loading module configuration
        $config = ArrayHelper::merge(
            require_once(__DIR__ . '/config/web.php'),
            require_once(__DIR__ . '/config/' . YII_ENV .'/web.php')
        );

        // initialize the module with the configuration loaded
        \Yii::configure($this, $config);

        // registering translations
        if (isset($config['components']['i18n'])) {
            $this->registerTranslations($config['components']['i18n']['translations']);
        }
    }

    /**
     * Register module translations
     * @param array $translations
     */
    protected function registerTranslations(array $translations)
    {
        foreach ($translations as $index => $translation) {
            \Yii::$app->i18n->translations[$index] = $translation;
        }
    }
}