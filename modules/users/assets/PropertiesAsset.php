<?php
namespace users\assets;

use yii\web\AssetBundle;

/**
 * Class PropertiesAsset
 *
 * @package users\assets
 */
class PropertiesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@users/views/properties/assets';
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
        'app\widgets\form\DropDownInputAsset',
        'yii\widgets\PjaxAsset',
    ];
}