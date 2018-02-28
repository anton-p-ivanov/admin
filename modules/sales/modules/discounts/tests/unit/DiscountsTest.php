<?php

namespace sales\modules\admin\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use sales\modules\discounts\models\Discount;
use sales\modules\discounts\tests\fixtures\DiscountFixture;

/**
 * Class DiscountsTest
 *
 * @package sales\modules\admin\tests
 */
class DiscountsTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            DiscountFixture::class,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        $this->faker = Factory::create();
    }

    /**
     * Testing validations.
     */
    public function testValidate()
    {
        $discount = new Discount();

        // Empty required fields
        $this->assertFalse($discount->validate(['title']));

        // Non-unique code
        $discount->code = 'discount_0';
        $this->assertFalse($discount->validate(['code']));

        // Long fields
        $discount->title = $this->faker->text(500);
        $this->assertFalse($discount->validate(['title']));

        $discount->code = $this->faker->text(500);
        $this->assertFalse($discount->validate(['code']));

        $discount->value = $this->faker->text();
        $this->assertFalse($discount->validate(['value']));
    }

    /**
     * Testing create
     */
    public function testCreate()
    {
        $discount = new Discount([
            'title' => $this->faker->text(),
            'code' => 'STATUS_CREATE_TEST'
        ]);

        $result = $discount->insert();

        // Test whether field was created.
        $this->assertTrue($result);
        $this->makeSure($discount);
    }

    /**
     * Testing update
     */
    public function testUpdate()
    {
        $discount = Discount::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'discount-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($discount);

        $discount->title = 'Updated title';
        $result = $discount->save();

        $this->assertTrue($result);

        $this->makeSure($discount);
    }

    /**
     * Testing copy
     */
    public function testCopy()
    {
        $discount = Discount::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'discount-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($discount);

        // Creating form field clone
        $clone = $discount->duplicate();
        $clone->code = $clone->code . '_clone';
        $result = $clone->save();

        $this->assertTrue($result);

        $this->makeSure($clone);
    }

    /**
     * Testing delete.
     */
    public function testDelete()
    {
        $discount = Discount::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'discount-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($discount);

        // Delete user field
        $result = $discount->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($discount->refresh());
        $this->assertEquals(0, (int) $discount->getWorkflow()->count());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Discount $discount
     */
    protected function makeSure($discount)
    {
        $this->assertTrue($discount instanceof Discount);
        $this->assertTrue($discount->workflow instanceof Workflow);
    }
}