<?php
namespace training\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class QuestionsFixture
 *
 * @package training\modules\admin\tests\fixtures
 */
class QuestionsFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'training\modules\admin\models\Question';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/questions.php';
}