<?php

namespace app\widgets;

use yii\web\AssetBundle;

/**
 * Class TabsAsset
 * @package app\widgets
 */
class TabsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/widgets/assets';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $css = [
        'Tabs.css',
    ];
    public $js = [
        'Tabs.js',
    ];
    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
