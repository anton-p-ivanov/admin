<?php

namespace partnership;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package partnership
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
    public $title = 'Partnership';

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
        \Yii::$app->i18n->translations['partnership*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@partnership/messages',
        ];

        \Yii::$app->i18n->translations['statuses*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@partnership/messages',
        ];
    }
}