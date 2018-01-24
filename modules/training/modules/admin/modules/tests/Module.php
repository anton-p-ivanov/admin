<?php

namespace training\modules\admin\modules\tests;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package training\modules\admin\modules\tests
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'tests';
    /**
     * @var string
     */
    public static $title = 'Tests';

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
//        \Yii::setAlias('@training', '@app/modules/training');
//        \Yii::setAlias('@tests', '@training/modules/admin/modules/tests');
//
//        $this->registerTranslations();
    }
//
//    /**
//     * Register module translations
//     */
//    protected function registerTranslations()
//    {
//        \Yii::$app->i18n->translations['tests*'] = [
//            'class' => 'yii\i18n\PhpMessageSource',
//            'basePath' => '@tests/messages',
//        ];
//    }
}