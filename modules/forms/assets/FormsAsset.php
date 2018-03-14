<?php
namespace forms\assets;

use yii\web\AssetBundle;

/**
 * Class FormsAsset
 *
 * @package forms\assets
 */
class FormsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@forms/views/forms/assets';
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