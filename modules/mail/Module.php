<?php

namespace mail;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package mail
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'templates';
    /**
     * @var string
     */
    public static $title = 'Mailing';

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
        \Yii::$app->i18n->translations['mail*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@mail/messages',
            'fileMap' => [
                'mail/types' => 'types.php',
                'mail/templates' => 'templates.php',
            ]
        ];
    }
}