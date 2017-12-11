<?php
namespace users\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class WorkflowFixture
 * @package users\modules\fields\tests\fixtures
 */
class WorkflowFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'app\models\Workflow';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/workflow.php';
}