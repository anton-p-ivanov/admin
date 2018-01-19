<?php

namespace catalogs\modules\admin;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package catalogs\modules\admin
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'catalogs';
    /**
     * @var string
     */
    public static $title = 'Manage catalogs';

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

        // set alias
        \Yii::setAlias('@catalogs', '@app/modules/catalogs');

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
        ];
    }
}