<?php

namespace forms;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package forms
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
    public $title = 'Web-forms';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $config = require_once(__DIR__ . '/config/web.php');
        $env = require_once(__DIR__ . '/config/' . YII_ENV . '/web.php');

        // initialize the module with the configuration loaded from config file
        \Yii::configure($this, ArrayHelper::merge($config, $env));

        // set alias
        \Yii::setAlias('@fields', '@app/modules/fields');

        $this->registerTranslations();
    }

    /**
     * Register module translations
     */
    protected function registerTranslations()
    {
        \Yii::$app->i18n->translations['forms*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@forms/messages',
        ];
    }
}