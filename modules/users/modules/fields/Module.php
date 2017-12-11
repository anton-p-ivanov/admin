<?php

namespace users\modules\fields;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package users\modules\fields
 */
class Module extends \yii\base\Module
{
    /**
     * @var array
     */
    public $controllerMap = [
        'values' => 'fields\controllers\ValuesController',
        'validators' => 'fields\controllers\ValidatorsController'
    ];
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

        // set alias
        \Yii::setAlias('@fields', '@app/modules/fields');

        // set view path
        $this->viewPath = '@fields/views';

        $this->registerTranslations();
    }

    /**
     * Register module translations
     */
    protected function registerTranslations()
    {
        \Yii::$app->i18n->translations['fields*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@users/modules/fields/messages',
        ];
    }
}