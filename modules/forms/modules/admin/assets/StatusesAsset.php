<?php
namespace forms\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Class StatusesAsset
 *
 * @package forms\modules\admin\assets
 */
class StatusesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@forms/modules/admin/views/statuses/assets';
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
        'app\widgets\TabsAsset',
        'yii\widgets\PjaxAsset',
    ];
}