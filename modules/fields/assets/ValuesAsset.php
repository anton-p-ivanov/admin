<?php
namespace fields\assets;

use yii\web\AssetBundle;

/**
 * Class ValuesAsset
 *
 * @package fields\assets
 */
class ValuesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@fields/views/values/assets';
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
        'app\themes\material\assets\FormAsset',
        'yii\widgets\PjaxAsset',
    ];
}