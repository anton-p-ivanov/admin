<?php

namespace accounts\tests;

use accounts\models\AccountAddress;
use accounts\models\Address;
use app\models\AddressCountry;
use app\models\AddressType;
use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use yii\db\Expression;

/**
 * Class AddressesTest
 * @package accounts\tests
 */
class AddressesTest extends Unit
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
            'account_addresses' => 'accounts\tests\fixtures\AccountsAddressesFixture',
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
    public function testValidate()
    {
        $address = new Address();

        // empty fields
        $address->zip = null;
        $this->assertFalse($address->validate(['zip']));

        $address->address = null;
        $this->assertFalse($address->validate(['address']));

        $address->city = null;
        $this->assertFalse($address->validate(['city']));

        $address->account_uuid = null;
        $this->assertNull($address->account_uuid);

        $address->country_code = 'INVALID_COUNTRY_CODE';
        $this->assertFalse($address->validate(['country_code']));
    }

    /**
     * Testing element creation.
     */
    public function testCreate()
    {
        $address = new Address([
            'type_uuid' => AddressType::find()->orderBy(new Expression('RAND()'))->one()->{'uuid'},
            'country_code' => AddressCountry::find()->orderBy(new Expression('RAND()'))->one()->{'code'},
            'region' => '',
            'district' => '',
            'city' => $this->faker->city,
            'zip' => $this->faker->postcode,
            'address' => $this->faker->address,
        ]);

        $address->account_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'account-0')->toString();
        $result = $address->insert();

        // Test whether element was created.
        $this->assertTrue($result);

        // Test on valid relation.
        $this->assertTrue($address->getType()->one() instanceof AddressType);
        $this->assertTrue($address->getCountry()->one() instanceof AddressCountry);
    }

    /**
     * Testing updating element attributes.
     */
    public function testUpdate()
    {
        $address = Address::findOne(['uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'address-0')->toString()]);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($address);

        $address->address = $this->faker->text();

        $result = $address->update();
        $address->refresh();

        // Test whether element has been updated.
        $this->assertTrue($result > 0);
    }

    /**
     * Testing copying of element.
     */
    public function testCopy()
    {
        $account_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'account-0')->toString();

        $address = Address::findOne(['uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'address-0')->toString()]);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($address);

        $count = AccountAddress::find()->where(['account_uuid' => $account_uuid])->count();

        // Creating an ActiveRecord clone
        $clone = $address->duplicate();
        $clone->{'account_uuid'} = $account_uuid;

        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertEquals($count + 1, (int)AccountAddress::find()->where(['account_uuid' => $account_uuid])->count());
    }

    /**
     * Testing element deletion with all related records.
     */
    public function testDelete()
    {
        $address = Address::findOne(['uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'address-0')->toString()]);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($address);

        // Delete user field
        $result = $address->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($address->refresh());

        // Make sure address has been removed from AccountAddress model
        $this->assertEquals(0, (int)AccountAddress::find()->where(['address_uuid' => $address->uuid])->count());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Address $address
     */
    protected function makeSure($address)
    {
        $this->assertTrue($address instanceof Address);
        $this->assertTrue($address->getType()->one() instanceof AddressType);
        $this->assertTrue($address->getCountry()->one() instanceof AddressCountry);

        $this->assertEquals(1, (int)AccountAddress::find()->where(['address_uuid' => $address->uuid])->count());
    }
}