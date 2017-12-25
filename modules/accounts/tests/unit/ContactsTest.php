<?php

namespace accounts\tests;

use accounts\models\Account;
use accounts\models\AccountContact;
use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;

/**
 * Class ContactsTest
 * @package accounts\tests
 */
class ContactsTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'workflow' => 'accounts\tests\fixtures\WorkflowFixture',
            'accounts' => 'accounts\tests\fixtures\AccountsFixture',
            'contacts' => 'accounts\tests\fixtures\ContactsFixture',
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@accounts', '@app/modules/accounts');

        $this->faker = Factory::create();
    }

    /**
     * Testing validations.
     */
    public function testContactValidate()
    {
        $contact = new AccountContact();

        // empty fields
        $contact->fullname = null;
        $this->assertFalse($contact->validate(['fullname']));

        $contact->email = null;
        $this->assertFalse($contact->validate(['email']));

        $contact->position = null;
        $this->assertFalse($contact->validate(['position']));

        $contact->account_uuid = null;
        $this->assertFalse($contact->validate(['account_uuid']));

        $contact->email = AccountContact::find()->one()->{'email'};
        $this->assertFalse($contact->validate(['email']));

        $contact->account_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'invalid-account-uuid')->toString();
        $this->assertFalse($contact->validate(['account_uuid']));

        $contact->user_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'invalid-user-uuid')->toString();
        $this->assertFalse($contact->validate(['user_uuid']));
    }

    /**
     * Testing element creation.
     */
    public function testContactCreate()
    {
        $contact = new AccountContact([
            'account_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'account-0')->toString(),
            'user_uuid' => null,
            'email' => $this->faker->email,
            'fullname' => $this->faker->name,
            'position' => $this->faker->jobTitle,
            'sort' => 100,
        ]);

        $result = $contact->insert();

        // Test whether element was created.
        $this->assertTrue($result);

        // Test on valid relation.
        $this->assertTrue($contact->getAccount()->one() instanceof Account);
    }

    /**
     * Testing updating element attributes.
     */
    public function testContactUpdate()
    {
        $contact = AccountContact::findOne(['uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'contact-1')->toString()]);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($contact);

        $account_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'account-0')->toString();
        $count = (int)AccountContact::find()->where(['account_uuid' => $account_uuid])->count();

        $contact->email = $this->faker->email;
        $contact->position = $this->faker->jobTitle;
        $contact->fullname = $this->faker->name;
        $contact->account_uuid = $account_uuid;

        $result = $contact->update();
        $contact->refresh();

        // Test whether element has been updated.
        $this->assertTrue($result > 0);

        // Test whether element has been attached to other Account
        $this->assertEquals((int)AccountContact::find()->where(['account_uuid' => $account_uuid])->count(), $count + 1);
    }

    /**
     * Testing copying of element.
     */
    public function testContactCopy()
    {
        $contact = AccountContact::findOne(['uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'contact-0')->toString()]);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($contact);

        // Creating an ActiveRecord clone
        $clone = $contact->duplicate();
        $clone->email = $this->faker->email;
        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($clone->getAccount()->one() instanceof Account);
    }

    /**
     * Testing element deletion with all related records.
     */
    public function testContactDelete()
    {
        $contact = AccountContact::findOne(['uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'contact-0')->toString()]);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($contact);

        // Delete user field
        $result = $contact->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($contact->refresh());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param AccountContact $contact
     */
    protected function makeSure($contact)
    {
        $this->assertTrue($contact instanceof AccountContact);
        $this->assertTrue($contact->getAccount()->one() instanceof Account);
    }
}