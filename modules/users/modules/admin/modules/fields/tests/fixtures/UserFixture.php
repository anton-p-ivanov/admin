<?php
namespace users\modules\admin\modules\fields\tests\fixtures;

use users\models\User;
use yii\test\ActiveFixture;

/**
 * Class UserFixture
 *
 * @package users\modules\admin\modules\fields\tests\fixtures
 */
class UserFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = User::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/users.php';
    /**
     * @var array
     */
    public $depends = [
        WorkflowFixture::class
    ];
}