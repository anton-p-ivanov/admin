<?php
namespace accounts\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class AccountsFixture
 * @package accounts\tests\fixtures
 */
class AccountsFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'accounts\models\Account';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/accounts.php';
}