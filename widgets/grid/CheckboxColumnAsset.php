<?php

namespace app\widgets\grid;

use yii\web\AssetBundle;

/**
 * Class CheckboxColumnAsset
 * @package app\widgets\grid
 */
class CheckboxColumnAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/widgets/grid/assets';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $js = [
        'CheckboxColumn.js',
    ];
    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
