<?php

namespace app\themes\material\assets;

use yii\web\AssetBundle;

/**
 * Class CodeMirrorAsset
 *
 * @package app\themes\material\assets
 */
class CodeMirrorAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/codemirror';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $css = [
        'lib/codemirror.css',
    ];
    /**
     * @var array
     */
    public $js = [
        'lib/codemirror.js',
        'mode/xml/xml.js',
        'mode/javascript/javascript.js',
        'mode/css/css.js',
        'mode/htmlmixed/htmlmixed.js'
    ];
    /**
     * @var array
     */
    public $depends = [];

}
