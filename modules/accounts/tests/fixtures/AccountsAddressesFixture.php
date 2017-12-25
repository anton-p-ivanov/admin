<?php
namespace accounts\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class AccountsAddressesFixture
 * @package accounts\tests\fixtures
 */
class AccountsAddressesFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'accounts\models\AccountAddress';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/accounts_addresses.php';
    /**
     * @var array
     */
    public $depends = [
        '\accounts\tests\fixtures\AccountsFixture',
        '\accounts\tests\fixtures\AddressesFixture',
    ];
}