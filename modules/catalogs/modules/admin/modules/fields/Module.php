<?php

namespace catalogs\modules\admin\modules\fields;

use yii\helpers\ArrayHelper;

/**
 * Class Module
 *
 * @package catalogs\modules\admin\modules\fields
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $defaultRoute = 'fields';
    /**
     * @var string
     */
    public static $title = 'Fields';

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
        \Yii::setAlias('@fields', '@app/modules/fields');
    }
}