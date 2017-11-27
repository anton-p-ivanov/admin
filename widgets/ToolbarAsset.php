<?php

namespace app\widgets;

use yii\web\AssetBundle;

/**
 * Class ToolbarAsset
 * @package app\widgets
 */
class ToolbarAsset extends AssetBundle
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
        'Toolbar.css',
    ];
    /**
     * @var array
     */
    public $depends = [
        'app\themes\material\assets\AppAsset'
    ];

}
