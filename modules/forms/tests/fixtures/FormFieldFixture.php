<?php
namespace forms\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FormFieldFixture
 * @package forms\tests\fixtures
 */
class FormFieldFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'forms\modules\fields\models\Field';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/forms_fields.php';
}