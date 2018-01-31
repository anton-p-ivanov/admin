<?php
namespace catalogs\modules\admin\modules\fields\tests\fixtures;

use catalogs\modules\admin\models\Type;
use yii\test\ActiveFixture;

/**
 * Class TypeFixture
 *
 * @package catalogs\modules\admin\modules\fields\tests\fixtures
 */
class TypeFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Type::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/types.php';
}