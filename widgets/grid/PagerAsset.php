<?php

namespace app\widgets\grid;

use yii\web\AssetBundle;

/**
 * Class PagerAsset
 * @package app\widgets\grid
 */
class PagerAsset extends AssetBundle
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
        'Pager.css',
    ];
    /**
     * @var array
     */
    public $depends = [
        'app\themes\material\assets\AppAsset'
    ];

}
