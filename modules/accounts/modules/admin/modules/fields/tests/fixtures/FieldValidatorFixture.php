<?php
namespace accounts\modules\admin\modules\fields\tests\fixtures;

use accounts\modules\admin\modules\fields\models\FieldValidator;
use yii\test\ActiveFixture;

/**
 * Class FieldValidatorFixture
 *
 * @package accounts\modules\admin\modules\fields\tests\fixtures
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
        FieldFixture::class
    ];
}