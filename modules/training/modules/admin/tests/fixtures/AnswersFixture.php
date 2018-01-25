<?php
namespace training\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class AnswersFixture
 *
 * @package training\modules\admin\tests\fixtures
 */
class AnswersFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'training\modules\admin\models\Answer';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/answers.php';
}