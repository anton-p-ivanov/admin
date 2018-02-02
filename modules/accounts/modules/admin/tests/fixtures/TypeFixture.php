<?php
namespace accounts\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class TypeFixture
 *
 * @package accounts\modules\admin\tests\fixtures
 */
class TypeFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'accounts\models\Type';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/types.php';
}