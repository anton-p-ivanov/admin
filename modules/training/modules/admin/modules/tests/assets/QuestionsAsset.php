<?php
namespace training\modules\admin\modules\tests\assets;

use yii\web\AssetBundle;

/**
 * Class QuestionsAsset
 *
 * @package training\modules\admin\modules\tests\assets
 */
class QuestionsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@tests/views/questions/assets';
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