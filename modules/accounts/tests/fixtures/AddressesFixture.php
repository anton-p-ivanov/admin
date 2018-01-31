<?php
namespace accounts\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class AddressesFixture
 * @package accounts\tests\fixtures
 */
class AddressesFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'app\models\Address';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/addresses.php';
}