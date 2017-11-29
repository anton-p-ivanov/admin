<?php

namespace app\widgets\form;

use yii\web\AssetBundle;

/**
 * Class FieldSelectorAsset
 * @package app\widgets\form
 */
class FieldSelectorAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/widgets/form/assets';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $css = [
        'FieldSelector.css',
    ];
    public $js = [
        'FieldSelector.js',
    ];
    /**
     * @var array
     * @todo must depends on JqueryAsset and DropDownAsset
     */
    public $depends = [
        'app\themes\material\assets\AppAsset'
    ];
}
