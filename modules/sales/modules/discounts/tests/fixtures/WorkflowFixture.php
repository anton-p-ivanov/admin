<?php
namespace sales\modules\discounts\tests\fixtures;

use app\models\Workflow;
use i18n\modules\admin\tests\fixtures\LanguageFixture;
use yii\test\ActiveFixture;

/**
 * Class WorkflowFixture
 *
 * @package sales\modules\discounts\tests\fixtures
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
    /**
     * @var array
     */
    public $depends = [
        LanguageFixture::class
    ];
}