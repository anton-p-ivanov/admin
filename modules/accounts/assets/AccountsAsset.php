<?php
namespace accounts\assets;

use yii\web\AssetBundle;

/**
 * Class AccountsAsset
 * @package accounts\assets
 */
class AccountsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@accounts/views/accounts/assets';
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
        'app\widgets\TabsAsset',
        'yii\widgets\PjaxAsset',
    ];
}