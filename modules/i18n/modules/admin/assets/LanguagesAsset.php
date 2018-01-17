<?php
namespace i18n\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Class LanguagesAsset
 * @package i18n\modules\admin\assets
 */
class LanguagesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@i18n/modules/admin/views/languages/assets';
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
        'yii\widgets\PjaxAsset',
    ];
}