<?php

namespace storage;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package storage
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public static $title = 'Storage';
    /**
     * @var string
     */
    public $defaultRoute = 'storage';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $config = require_once(__DIR__ . '/config/web.php');
        $env = require_once(__DIR__ . '/config/' . YII_ENV .'/web.php');

        // initialize the module with the configuration loaded from config.php
        \Yii::configure(\Yii::$app, ArrayHelper::merge($config, $env));
    }
}