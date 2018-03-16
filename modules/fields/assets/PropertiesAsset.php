<?php
namespace fields\assets;

use yii\web\AssetBundle;

/**
 * Class PropertiesAsset
 *
 * @package fields\assets
 */
class PropertiesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@fields/views/properties/assets';
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
        'app\widgets\form\DropDownInputAsset',
        'app\widgets\form\FileAsset',
        'yii\widgets\PjaxAsset',
    ];
}