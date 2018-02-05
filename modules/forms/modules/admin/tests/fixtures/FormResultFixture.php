<?php
namespace forms\modules\admin\tests\fixtures;

use forms\modules\admin\models\FormResult;
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
    public $modelClass = FormResult::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/results.php';
}