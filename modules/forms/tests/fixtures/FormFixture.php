<?php
namespace forms\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FormFixture
 * @package forms\tests\fixtures
 */
class FormFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'forms\models\Form';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/forms.php';
    /**
     * @var array
     */
    public $depends = [
        'workflow' => 'forms\tests\fixtures\WorkflowFixture'
    ];
}