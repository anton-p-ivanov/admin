<?php
namespace accounts\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FieldFixture
 * @package accounts\modules\fields\tests\fixtures
 */
class FieldFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'accounts\modules\fields\models\Field';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields.php';
    /**
     * @var array
     */
    public $depends = [
        'workflow' => 'accounts\modules\fields\tests\fixtures\WorkflowFixture'
    ];
}