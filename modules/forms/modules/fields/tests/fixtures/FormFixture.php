<?php
namespace forms\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FormFixture
 * @package forms\modules\fields\tests\fixtures
 */
class FormFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'forms\models\Form';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/forms.php';
}