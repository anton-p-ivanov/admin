<?php
namespace users\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FieldFixture
 * @package users\modules\fields\tests\fixtures
 */
class FieldFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'users\modules\fields\models\Field';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields.php';
    /**
     * @var array
     */
    public $depends = [
        'workflow' => 'users\modules\fields\tests\fixtures\WorkflowFixture'
    ];
}