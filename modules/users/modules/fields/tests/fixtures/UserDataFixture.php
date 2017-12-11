<?php
namespace users\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class UserDataFixture
 * @package users\modules\fields\tests\fixtures
 */
class UserDataFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'users\models\UserData';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/users_data.php';
}