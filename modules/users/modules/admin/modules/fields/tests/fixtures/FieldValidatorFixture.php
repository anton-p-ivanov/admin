<?php
namespace users\modules\admin\modules\fields\tests\fixtures;

use users\modules\admin\modules\fields\models\FieldValidator;
use yii\test\ActiveFixture;

/**
 * Class FieldValidatorFixture
 *
 * @package users\modules\admin\modules\fields\tests\fixtures
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