<?php
namespace users\assets;

use yii\web\AssetBundle;

/**
 * Class SitesAsset
 *
 * @package users\assets
 */
class SitesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@users/views/sites/assets';
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
        'app\themes\material\assets\DateTimePickerAsset',
        'app\widgets\form\DropDownInputAsset',
        'yii\widgets\PjaxAsset',
    ];
}