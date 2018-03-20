<?php
namespace mail\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Class TemplatesAsset
 *
 * @package mail\modules\admin\assets
 */
class TemplatesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@mail/modules/admin/views/templates/assets';
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
        'app\themes\material\assets\CodeMirrorThemeAsset',
        'app\widgets\form\FieldSelectorAsset',
        'app\widgets\form\DropDownInputAsset',
        'app\widgets\TabsAsset',
        'yii\widgets\PjaxAsset',
    ];
}