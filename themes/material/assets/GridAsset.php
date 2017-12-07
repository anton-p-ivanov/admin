<?php

namespace app\themes\material\assets;

use yii\web\AssetBundle;

/**
 * Class GridAsset
 * @package app\themes\material\assets
 */
class GridAsset extends AssetBundle
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
    public $js = [
        'js/grid.js',
    ];
    /**
     * @var array
     */
    public $depends = [
        'app\themes\material\assets\AppAsset'
    ];

}
