<?php
namespace training\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class TestsQuestionsFixture
 *
 * @package training\modules\admin\tests\fixtures
 */
class TestsQuestionsFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'training\modules\admin\models\TestQuestion';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/tests_questions.php';
}