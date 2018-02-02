<?php
namespace users\modules\admin\modules\fields\assets;

use yii\web\AssetBundle;

/**
 * Class ValuesAsset
 *
 * @package users\modules\admin\modules\fields\assets
 */
class ValuesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@users/modules/admin/modules/fields/views/values/assets';
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