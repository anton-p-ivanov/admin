<?php
namespace partnership\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class StatusFixture
 * @package partnership\tests\fixtures
 */
class StatusFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'partnership\models\Status';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/statuses.php';
}