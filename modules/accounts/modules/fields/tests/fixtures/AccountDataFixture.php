<?php
namespace accounts\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class AccountDataFixture
 * @package accounts\modules\fields\tests\fixtures
 */
class AccountDataFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'accounts\models\AccountData';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/accounts_data.php';
}