<?php
namespace sales\modules\discounts\assets;

use yii\web\AssetBundle;

/**
 * Class DiscountsAsset
 *
 * @package sales\modules\discounts\assets
 */
class DiscountsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@sales/modules/discounts/views/discounts/assets';
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
        'yii\widgets\PjaxAsset',
    ];
}