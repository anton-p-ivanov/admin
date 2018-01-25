<?php
namespace training\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class LessonsFixture
 *
 * @package training\modules\admin\tests\fixtures
 */
class LessonsFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'training\modules\admin\models\Lesson';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/lessons.php';
}