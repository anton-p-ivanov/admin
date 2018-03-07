<?php
namespace fields\assets;

use yii\web\AssetBundle;

/**
 * Class GroupsAsset
 *
 * @package fields\assets
 */
class GroupsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@fields/views/groups/assets';
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
        'yii\widgets\PjaxAsset',
    ];
}