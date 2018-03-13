<?php

namespace app\themes\material\assets;

use yii\web\AssetBundle;

/**
 * Class CodeMirrorThemeAsset
 *
 * @package app\themes\material\assets
 */
class CodeMirrorThemeAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/themes/material';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $css = [
        'css/cm-default-theme.css',
    ];
    /**
     * @var array
     */
    public $depends = [
        CodeMirrorAsset::class
    ];

}
