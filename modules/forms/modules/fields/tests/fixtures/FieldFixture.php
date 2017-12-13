<?php
namespace forms\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FieldFixture
 * @package forms\modules\fields\tests\fixtures
 */
class FieldFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'forms\modules\fields\models\Field';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields.php';
    /**
     * @var array
     */
    public $depends = [
        'workflow' => 'forms\modules\fields\tests\fixtures\WorkflowFixture'
    ];
}