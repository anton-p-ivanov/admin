<?php
namespace accounts\modules\admin\modules\fields\assets;

use yii\web\AssetBundle;

/**
 * Class FieldsAsset
 *
 * @package accounts\modules\admin\modules\fields\assets
 */
class FieldsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@accounts/modules/admin/modules/fields/views/fields/assets';
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
        'app\widgets\form\DropDownInputAsset',
        'app\widgets\TabsAsset',
        'yii\widgets\PjaxAsset',
    ];
}