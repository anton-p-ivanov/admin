<?php
namespace catalogs\modules\admin\modules\fields\tests\fixtures;

use app\models\Workflow;
use yii\test\ActiveFixture;

/**
 * Class WorkflowFixture
 *
 * @package catalogs\modules\admin\modules\fields\tests\fixtures
 */
class WorkflowFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Workflow::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/workflow.php';
}