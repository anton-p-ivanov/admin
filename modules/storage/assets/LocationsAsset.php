<?php
namespace storage\assets;

use yii\web\AssetBundle;

/**
 * Class LocationsAsset
 */
class LocationsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@storage/views/locations/assets';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $js = ['index.js'];
    /**
     * @var array
     */
    public $css = ['index.css'];
    /**
     * @var array
     */
    public $depends = [
        'app\themes\material\assets\AppAsset',
        'app\themes\material\assets\GridAsset',
        'yii\widgets\PjaxAsset',
    ];
}