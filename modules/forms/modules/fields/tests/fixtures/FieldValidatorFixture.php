<?php
namespace forms\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FieldValidatorFixture
 * @package forms\modules\fields\tests\fixtures
 */
class FieldValidatorFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'forms\modules\fields\models\FieldValidator';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields_validators.php';
    /**
     * @var array
     */
    public $depends = [
        'forms\modules\fields\tests\fixtures\FieldFixture'
    ];
}