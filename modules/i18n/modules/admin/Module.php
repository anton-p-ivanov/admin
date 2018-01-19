<?php

namespace i18n\modules\admin;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package i18n\modules\admin
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'languages';
    /**
     * @var string
     */
    public static $title = 'I18N';

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
        \Yii::setAlias('@catalogs', '@app/modules/i18n');

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