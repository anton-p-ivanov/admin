<?php
namespace accounts\assets;

use yii\web\AssetBundle;

/**
 * Class StatusesAsset
 *
 * @package accounts\assets
 */
class StatusesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@accounts/views/statuses/assets';
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
        'app\widgets\form\DropDownInputAsset',
        'yii\widgets\PjaxAsset',
    ];
}