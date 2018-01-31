<?php

namespace catalogs\modules\admin\modules\fields\tests;

use catalogs\modules\admin\modules\fields\models\FieldValue;
use catalogs\modules\admin\modules\fields\tests\fixtures\ValueFixture;
use Codeception\Test\Unit;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

/**
 * Class ValuesTest
 * @package catalogs\modules\admin\modules\fields\tests
 */
class ValuesTest extends Unit
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
            'values' => ValueFixture::className(),
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
     * Validate test
     */
    public function testValidate()
    {
        $value = new FieldValue();

        // Empty required fields
        $this->assertFalse($value->validate(['value']));
        $this->assertFalse($value->validate(['label']));

        $value->sort = -1;
        $this->assertFalse($value->validate(['sort']));

        $value->value = $this->faker->text(500);
        $value->label = $this->faker->text(500);
        $this->assertFalse($value->validate(['value']));
        $this->assertFalse($value->validate(['label']));
    }

    /**
     * Create test
     */
    public function testCreate()
    {
        $value = new FieldValue([
            'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-0')->toString(),
            'value' => $this->faker->text(),
            'label' => $this->faker->text(),
            'sort' => 100
        ]);

        $field = $value->field;
        $count = count($field->fieldValues);

        $result = $value->insert();

        // Test whether field was created.
        $this->assertTrue($result);

        $this->makeSure($value);
        $field->refresh();

        $this->assertTrue(count($field->fieldValues) - $count === 1);
    }

    /**
     * Update test
     */
    public function testUpdate()
    {
        $value = FieldValue::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'value-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($value);

        $value->value = $this->faker->text();
        $result = $value->update();

        $this->assertNotFalse($result);
    }

    /**
     * Copy test
     */
    public function testCopy()
    {
        $value = FieldValue::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'value-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($value);

        $field = $value->field;
        $count = count($field->fieldValues);

        /* @var FieldValue $clone */
        $clone = $value->duplicate();
        $clone->field_uuid = $value->field_uuid;

        $result = $clone->save();
        $field->refresh();

        $this->assertTrue($result);
        $this->makeSure($clone);
        $this->assertTrue(count($field->fieldValues) - $count === 1);
    }

    /**
     * Delete test
     */
    public function testDelete()
    {
        $value = FieldValue::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'value-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($value);

        $field = $value->field;
        $count = count($field->fieldValues);

        // Delete user field
        $result = $value->delete();
        $field->refresh();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($value->refresh());
        $this->assertTrue($count - count($field->fieldValues) === 1);
    }

    /**
     * @param FieldValue $value
     */
    protected function makeSure($value)
    {
        $this->assertTrue($value instanceof FieldValue);
        $this->assertNotNull($value->field);
    }
}