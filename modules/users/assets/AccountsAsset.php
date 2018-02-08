<?php
namespace users\assets;

use yii\web\AssetBundle;

/**
 * Class AccountsAsset
 *
 * @package users\assets
 */
class AccountsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@users/views/accounts/assets';
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
        'app\widgets\form\DropDownInputAsset',
        'yii\widgets\PjaxAsset',
    ];
}