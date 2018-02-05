<?php
namespace forms\modules\admin\modules\fields\tests\fixtures;

use forms\modules\admin\modules\fields\models\FieldValidator;
use forms\modules\admin\tests\fixtures\FormFieldFixture;
use yii\test\ActiveFixture;

/**
 * Class FieldValidatorFixture
 *
 * @package forms\modules\admin\modules\fields\tests\fixtures
 */
class FieldValidatorFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = FieldValidator::class;
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields_validators.php';
    /**
     * @var array
     */
    public $depends = [
        FormFieldFixture::class
    ];
}