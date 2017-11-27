<?php
namespace storage\assets;

use yii\web\AssetBundle;

/**
 * Class IndexAsset
 */
class IndexAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@storage/views/storage/assets';
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
        'storage\assets\UploaderAsset',
        'app\widgets\TabsAsset',
        'yii\widgets\PjaxAsset',
    ];
}