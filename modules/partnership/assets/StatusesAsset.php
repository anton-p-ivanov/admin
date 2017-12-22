<?php
namespace partnership\assets;

use yii\web\AssetBundle;

/**
 * Class StatusesAsset
 * @package partnership\assets
 */
class StatusesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@partnership/views/statuses/assets';
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
        'app\widgets\form\FieldSelectorAsset',
        'yii\widgets\PjaxAsset',
    ];
}