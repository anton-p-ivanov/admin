<?php

namespace app\themes\material\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package app\themes\material\assets
 */
class AppAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/themes/material';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $css = [
        'css/app.css',
    ];
    /**
     * @var array
     */
    public $js = [
        'js/app.js',
        'js/dropdown.js',
        'js/modal.js'
    ];
    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];

}
