<?php

namespace i18n;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package i18n
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public static $title = 'Internationalization';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $config = require_once(__DIR__ . '/config/web.php');
        $env = require_once(__DIR__ . '/config/' . YII_ENV .'/web.php');

        // initialize the module with the configuration loaded from config file
        \Yii::configure($this, ArrayHelper::merge($config, $env));

        $this->registerTranslations();
    }

    /**
     * Register module translations
     */
    protected function registerTranslations()
    {
        \Yii::$app->i18n->translations['i18n*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@i18n/messages',
        ];
    }
}