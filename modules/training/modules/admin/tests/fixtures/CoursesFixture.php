<?php
namespace training\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class CoursesFixture
 *
 * @package training\modules\admin\tests\fixtures
 */
class CoursesFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'training\modules\admin\models\Course';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/courses.php';
}