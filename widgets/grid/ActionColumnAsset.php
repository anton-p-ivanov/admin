<?php

namespace app\widgets\grid;

use yii\web\AssetBundle;

/**
 * Class ActionColumnAsset
 * @package app\widgets\grid
 */
class ActionColumnAsset extends AssetBundle
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
        'ActionColumn.css',
    ];
    /**
     * @var array
     */
    public $depends = [
        'app\themes\material\assets\AppAsset'
    ];
}
