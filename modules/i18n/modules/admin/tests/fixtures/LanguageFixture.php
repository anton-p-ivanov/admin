<?php
namespace i18n\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class LanguageFixture
 * @package i18n\modules\admin\tests\fixtures
 */
class LanguageFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'i18n\models\Language';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/languages.php';
}