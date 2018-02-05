<?php
namespace forms\modules\admin\modules\fields\assets;

use yii\web\AssetBundle;

/**
 * Class ValidatorsAsset
 *
 * @package forms\modules\admin\modules\fields\assets
 */
class ValidatorsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@forms/modules/admin/modules/fields/views/validators/assets';
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