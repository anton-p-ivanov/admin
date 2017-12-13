<?php
namespace forms\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MailTypeFixture
 * @package forms\tests\fixtures
 */
class MailTypeFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'mail\models\Type';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/mail_types.php';
}