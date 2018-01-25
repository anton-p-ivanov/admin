<?php
namespace training\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class TestsFixture
 *
 * @package training\modules\admin\tests\fixtures
 */
class TestsFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'training\modules\admin\models\Test';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/tests.php';
}