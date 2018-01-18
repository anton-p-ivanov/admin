<?php
namespace admin\assets;

use yii\web\AssetBundle;

/**
 * Class SitesAsset
 * @package admin\assets
 */
class SitesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@admin/views/sites/assets';
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
        'yii\widgets\PjaxAsset',
    ];
}