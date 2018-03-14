<?php
namespace forms\modules\admin\tests\fixtures;

use forms\modules\admin\models\Result;
use yii\test\ActiveFixture;

/**
 * Class FormResultFixture
 *
 * @package forms\modules\admin\tests\fixtures
 */
class FormResultFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Result::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/results.php';
}