<?php
namespace accounts\assets;

use yii\web\AssetBundle;

/**
 * Class AddressesAsset
 *
 * @package accounts\assets
 */
class AddressesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@accounts/views/addresses/assets';
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