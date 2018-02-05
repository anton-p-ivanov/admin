<?php
namespace forms\modules\admin\tests\fixtures;

use forms\models\FormStatus;
use yii\test\ActiveFixture;

/**
 * Class FormStatusFixture
 *
 * @package forms\modules\admin\tests\fixtures
 */
class FormStatusFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = FormStatus::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/statuses.php';
    /**
     * @var array
     */
    public $depends = [
        FormFixture::class,
        WorkflowFixture::class
    ];
}