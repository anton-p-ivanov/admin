<?php
namespace mail\assets;

use yii\web\AssetBundle;

/**
 * Class TypesAsset
 * @package mail\assets
 */
class TypesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@mail/views/types/assets';
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
        'yii\widgets\PjaxAsset',
    ];
}