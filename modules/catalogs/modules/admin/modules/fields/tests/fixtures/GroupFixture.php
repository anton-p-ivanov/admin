<?php
namespace catalogs\modules\admin\modules\fields\tests\fixtures;

use catalogs\modules\admin\modules\fields\models\Group;
use yii\test\ActiveFixture;

/**
 * Class GroupFixture
 *
 * @package catalogs\modules\admin\modules\fields\tests\fixtures
 */
class GroupFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Group::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/groups.php';
    /**
     * @var array
     */
    public $depends = [
        CatalogFixture::class,
        WorkflowFixture::class
    ];
}