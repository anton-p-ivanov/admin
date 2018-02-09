<?php
namespace users\modules\admin\tests\fixtures;

use users\modules\admin\models\Role;
use yii\test\ActiveFixture;

/**
 * Class RoleFixture
 *
 * @package users\modules\admin\tests\fixtures
 */
class RoleFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Role::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/roles.php';
    /**
     * @var array
     */
    public $depends = [
        RoleI18NFixture::class
    ];
}