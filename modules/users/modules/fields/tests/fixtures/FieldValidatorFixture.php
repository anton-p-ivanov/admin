<?php
namespace users\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FieldValidatorFixture
 * @package users\modules\fields\tests\fixtures
 */
class FieldValidatorFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'users\modules\fields\models\FieldValidator';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/fields_validators.php';
    /**
     * @var array
     */
    public $depends = [
        'users\modules\fields\tests\fixtures\FieldFixture'
    ];
}