<?php
namespace accounts\tests\fixtures;

use i18n\modules\admin\tests\fixtures\LanguageFixture;
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
    public $dataFile = __DIR__ . '/data/generated/types_i18n.php';
    /**
     * @var array
     */
    public $depends = [
        LanguageFixture::class
    ];
}