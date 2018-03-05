<?php
namespace storage\assets;

use yii\web\AssetBundle;

/**
 * Class StorageAsset
 */
class StorageAsset extends AssetBundle
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
        'app\themes\material\assets\GridAsset',
        'storage\assets\UploaderAsset',
        'yii\widgets\PjaxAsset',
    ];
}