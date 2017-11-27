<?php

namespace app\themes\material\assets;

use yii\web\AssetBundle;

/**
 * Class DateTimePickerAsset
 * @package app\themes\material\assets
 */
class DateTimePickerAsset extends AssetBundle
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
        'css/dt-picker.css',
    ];
    /**
     * @var array
     */
    public $js = [
        'js/moment.min.js',
        'js/dt-picker.js',
    ];
    /**
     * @var array
     */
    public $depends = [];

}
