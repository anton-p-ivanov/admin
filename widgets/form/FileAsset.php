<?php

namespace app\widgets\form;

use yii\web\AssetBundle;

/**
 * Class FileAsset
 *
 * @package app\widgets\form
 */
class FileAsset extends AssetBundle
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
        'File.css',
    ];
}
