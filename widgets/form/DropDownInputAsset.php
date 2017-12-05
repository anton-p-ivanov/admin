<?php

namespace app\widgets\form;

use yii\web\AssetBundle;

/**
 * Class DropDownInputAsset
 * @package app\widgets\form
 */
class DropDownInputAsset extends AssetBundle
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
    public $js = [
        'DropDownInput.js',
    ];
    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
