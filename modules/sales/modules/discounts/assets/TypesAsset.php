<?php
namespace sales\modules\discounts\assets;

use yii\web\AssetBundle;

/**
 * Class TypesAsset
 *
 * @package sales\modules\discounts\assets
 */
class TypesAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@sales/modules/discounts/views/types/assets';
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