<?php
namespace accounts\modules\admin\modules\fields\tests\fixtures;

use accounts\models\Account;
use yii\test\ActiveFixture;

/**
 * Class AccountFixture
 *
 * @package accounts\modules\admin\modules\fields\tests\fixtures
 */
class AccountFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Account::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/accounts.php';
    /**
     * @var array
     */
    public $depends = [
        WorkflowFixture::class
    ];
}