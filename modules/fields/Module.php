<?php

namespace fields;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package fields
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'fields';
    /**
     * @var string
     */
    public $title = 'Fields';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $config = require_once(__DIR__ . '/config/web.php');
        $env = require_once(__DIR__ . '/config/' . YII_ENV .'/web.php');

        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, ArrayHelper::merge($config, $env));

        $this->registerTranslations();
    }

    /**
     * Register module translations
     */
    protected function registerTranslations()
    {
        \Yii::$app->i18n->translations['fields*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@fields/messages',
        ];
    }
}