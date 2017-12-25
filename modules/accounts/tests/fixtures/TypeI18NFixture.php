<?php
namespace accounts\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class TypeI18NFixture
 * @package accounts\tests\fixtures
 */
class TypeI18NFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $tableName = '{{%accounts_types_i18n}}';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/types_i18n.php';
}