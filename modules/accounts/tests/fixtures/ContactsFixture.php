<?php
namespace accounts\tests\fixtures;

use yii\test\ActiveFixture;

/**
 * Class ContactsFixture
 * @package accounts\tests\fixtures
 */
class ContactsFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'accounts\models\AccountContact';
    /**
     * @var string
     */
    public $dataFile = __DIR__ . '/data/generated/contacts.php';
}