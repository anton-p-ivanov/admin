<?php
namespace sales\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Class DiscountsAsset
 *
 * @package sales\modules\admin\assets
 */
class DiscountsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@sales/modules/admin/views/discounts/assets';
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