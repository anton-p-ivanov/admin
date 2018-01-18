<?php
namespace catalogs\modules\admin\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class WorkflowFixture
 * @package catalogs\modules\admin\tests\fixtures
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