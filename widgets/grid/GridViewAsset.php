<?php

namespace app\widgets\grid;

use yii\web\AssetBundle;

/**
 * Class GridViewAsset
 * @package app\widgets\grid
 */
class GridViewAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/widgets/grid/assets';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $css = [
        'GridView.css',
    ];
    /**
     * @var array
     */
    public $depends = [
        'app\themes\material\assets\AppAsset'
    ];

}
