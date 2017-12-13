<?php
namespace forms\modules\fields\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FormResultFixture
 * @package forms\modules\fields\tests\fixtures
 */
class FormResultFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'forms\models\FormResult';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/forms_results.php';
}