<?php
namespace users\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Class RolesAsset
 *
 * @package users\modules\admin\assets
 */
class RolesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@users/modules/admin/views/roles/assets';
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
        'app\widgets\form\FieldSelectorAsset',
        'yii\widgets\PjaxAsset',
    ];
}