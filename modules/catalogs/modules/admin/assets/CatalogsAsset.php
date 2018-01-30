<?php
namespace catalogs\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Class CatalogsAsset
 * @package catalogs\modules\admin\assets
 */
class CatalogsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@catalogs/modules/admin/views/catalogs/assets';
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
        'app\widgets\form\DropDownInputAsset',
        'app\widgets\form\FieldSelectorAsset',
        'app\widgets\TabsAsset',
        'yii\widgets\PjaxAsset',
    ];
}