<?php
namespace users\assets;

use yii\web\AssetBundle;

/**
 * Class UsersAsset
 * @package users\assets
 */
class UsersAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@users/views/users/assets';
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
        'app\widgets\TabsAsset',
        'yii\widgets\PjaxAsset',
    ];
}