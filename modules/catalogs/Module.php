<?php

namespace catalogs;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package catalogs
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
    public static $title = 'Catalogs';

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
        \Yii::$app->i18n->translations['catalogs*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@catalogs/messages',
            'fileMap' => [
                'catalogs' => 'catalogs.php',
                'catalogs/catalogs' => 'catalogs.php',
                'catalogs/types' => 'types.php',
                'catalogs/fields/groups' => 'fields/groups.php',
            ]
        ];
    }
}