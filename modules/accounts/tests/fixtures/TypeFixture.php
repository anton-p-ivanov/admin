<?php
namespace accounts\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class TypeFixture
 * @package accounts\tests\fixtures
 */
class TypeFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'accounts\models\Type';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/types.php';
}