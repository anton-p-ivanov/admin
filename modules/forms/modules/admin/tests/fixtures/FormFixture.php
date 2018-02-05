<?php
namespace forms\modules\admin\tests\fixtures;

use forms\models\Form;
use yii\test\ActiveFixture;

/**
 * Class FormFixture
 *
 * @package forms\modules\admin\tests\fixtures
 */
class FormFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = Form::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/forms.php';
    /**
     * @var array
     */
    public $depends = [
        WorkflowFixture::class
    ];
}