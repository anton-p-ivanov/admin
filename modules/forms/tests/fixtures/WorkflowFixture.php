<?php
namespace forms\tests\fixtures;

use i18n\modules\admin\tests\fixtures\LanguageFixture;
use yii\test\ActiveFixture;

/**
 * Class WorkflowFixture
 * @package forms\tests\fixtures
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
    /**
     * @var array
     */
    public $depends = [
        LanguageFixture::class
    ];
}