<?php

namespace forms\modules\admin;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 *
 * @package forms\modules\admin
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
    public static $title = 'Forms';

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

        \Yii::setAlias('@fields', '@app/modules/fields');
    }
}