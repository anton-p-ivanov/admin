<?php

namespace training\modules\admin;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package training\modules\admin
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'courses';
    /**
     * @var string
     */
    public static $title = 'Training';

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
    }
}