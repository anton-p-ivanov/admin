<?php
namespace forms\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Class FormsAsset
 *
 * @package forms\modules\admin\assets
 */
class FormsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@forms/modules/admin/views/forms/assets';
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
        'app\themes\material\assets\DateTimePickerAsset',
        'app\widgets\form\DropDownInputAsset',
        'app\widgets\TabsAsset',
        'yii\widgets\PjaxAsset',
    ];
}