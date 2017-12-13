<?php
namespace forms\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class FormEventFixture
 * @package forms\tests\fixtures
 */
class FormEventFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'forms\models\FormEvent';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/forms_events.php';
    /**
     * @var array
     */
    public $depends = [
        '\forms\tests\fixtures\MailTypeFixture'
    ];
}