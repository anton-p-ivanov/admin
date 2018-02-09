<?php
namespace users\modules\admin\tests\fixtures;

use i18n\modules\admin\tests\fixtures\LanguageFixture;
use yii\test\ActiveFixture;

/**
 * Class RoleI18NFixture
 *
 * @package users\modules\admin\tests\fixtures
 */
class RoleI18NFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $tableName = '{{%auth_items_i18n}}';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/roles_i18n.php';
    /**
     * @var array
     */
    public $depends = [
        LanguageFixture::class
    ];
}