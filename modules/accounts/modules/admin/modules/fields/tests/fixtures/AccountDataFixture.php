<?php
namespace accounts\modules\admin\modules\fields\tests\fixtures;

use accounts\models\AccountData;
use yii\test\ActiveFixture;

/**
 * Class AccountDataFixture
 *
 * @package accounts\modules\admin\modules\fields\tests\fixtures
 */
class AccountDataFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = AccountData::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/accounts_data.php';
    /**
     * @var array
     */
    public $depends = [
        AccountFixture::class
    ];
}