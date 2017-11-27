<?php

namespace storage\assets;

use yii\web\AssetBundle;

/**
 * Class UploaderAsset
 * @package storage\assets
 */
class UploaderAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/blueimp/jquery-file-upload/js';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $js = [
        'vendor/jquery.ui.widget.js',
        'jquery.fileupload.js',
    ];
    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
