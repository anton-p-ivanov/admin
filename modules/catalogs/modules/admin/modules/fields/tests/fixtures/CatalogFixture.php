<?php
namespace catalogs\modules\admin\modules\fields\tests\fixtures;

use catalogs\modules\admin\models\Catalog;
use yii\test\ActiveFixture;

/**
 * Class CatalogFixture
 *
 * @package catalogs\modules\admin\modules\fields\tests\fixtures
 */
class CatalogFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Catalog::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/catalogs.php';
    /**
     * @var array
     */
    public $depends = [
        TypeFixture::class,
        WorkflowFixture::class
    ];
}