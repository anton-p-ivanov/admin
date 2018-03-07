<?php
namespace catalogs\modules\admin\modules\fields\assets;

use yii\web\AssetBundle;

/**
 * Class FieldsAsset
 * @package catalogs\modules\admin\modules\fields\assets
 */
class FieldsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@catalogs/modules/admin/modules/fields/views/fields/assets';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $css = ['index.css'];
    /**
     * @var array
     */
    public $depends = [
        \fields\assets\FieldsAsset::class
    ];
}