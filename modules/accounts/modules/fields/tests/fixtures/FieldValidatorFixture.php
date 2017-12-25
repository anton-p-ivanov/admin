<?php
namespace accounts\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FieldValidatorFixture
 * @package accounts\modules\fields\tests\fixtures
 */
class FieldValidatorFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'accounts\modules\fields\models\FieldValidator';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields_validators.php';
    /**
     * @var array
     */
    public $depends = [
        'accounts\modules\fields\tests\fixtures\FieldFixture'
    ];
}