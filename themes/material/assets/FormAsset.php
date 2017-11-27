<?php

namespace app\themes\material\assets;

use yii\web\AssetBundle;

/**
 * Class FormAsset
 * @package app\themes\material\assets
 */
class FormAsset extends AssetBundle
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
        'css/form.css',
    ];
    /**
     * @var array
     */
    public $js = [
        'js/form.js',
    ];
    /**
     * @var array
     */
    public $depends = [];

}
