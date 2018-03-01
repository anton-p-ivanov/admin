<?php
namespace users\modules\admin\modules\fields\tests\fixtures;

use users\models\UserProperty;
use yii\test\ActiveFixture;

/**
 * Class UserDataFixture
 *
 * @package users\modules\admin\modules\fields\tests\fixtures
 */
class UserDataFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = UserProperty::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/users_data.php';
    /**
     * @var array
     */
    public $depends = [
        UserFixture::class
    ];
}