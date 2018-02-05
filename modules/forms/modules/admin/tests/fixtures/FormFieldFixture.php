<?php
namespace forms\modules\admin\tests\fixtures;

use forms\modules\admin\modules\fields\models\Field;
use yii\test\ActiveFixture;

/**
 * Class FormFieldFixture
 *
 * @package forms\modules\admin\tests\fixtures
 */
class FormFieldFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Field::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/fields.php';
    /**
     * @var array
     */
    public $depends = [
        FormFixture::class,
        WorkflowFixture::class
    ];
}