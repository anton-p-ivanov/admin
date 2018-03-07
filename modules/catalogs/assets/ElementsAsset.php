<?php
namespace catalogs\assets;

use yii\web\AssetBundle;

/**
 * Class ElementsAsset
 */
class ElementsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@catalogs/views/elements/assets';
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
        'app\themes\material\assets\FormAsset',
        'app\themes\material\assets\GridAsset',
        'app\themes\material\assets\DateTimePickerAsset',
        'app\themes\material\assets\CodeMirrorThemeAsset',
        'app\widgets\TabsAsset',
        'yii\widgets\PjaxAsset',
    ];
}