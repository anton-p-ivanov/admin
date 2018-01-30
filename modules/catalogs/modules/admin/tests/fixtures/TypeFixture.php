<?php
namespace catalogs\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class TypeFixture
 * @package catalogs\modules\admin\tests\fixtures
 */
class TypeFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'catalogs\models\Type';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/types.php';
}