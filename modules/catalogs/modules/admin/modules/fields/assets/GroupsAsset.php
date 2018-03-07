<?php
namespace catalogs\modules\admin\modules\fields\assets;

use yii\web\AssetBundle;

/**
 * Class GroupsAsset
 *
 * @package catalogs\modules\admin\modules\fields\assets
 */
class GroupsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@catalogs/modules/admin/modules/fields/views/groups/assets';
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
        \fields\assets\GroupsAsset::class
    ];
}