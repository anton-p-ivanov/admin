<?php
namespace accounts\modules\admin\modules\fields\tests\fixtures;

use accounts\models\AccountProperty;
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
    public $modelClass = AccountProperty::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/accounts_properties.php';
    /**
     * @var array
     */
    public $depends = [
        AccountFixture::class
    ];
}