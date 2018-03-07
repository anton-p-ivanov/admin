<?php

namespace catalogs\modules\admin\modules\fields\tests;

use catalogs\modules\admin\modules\fields\models\FieldValidator;
use catalogs\modules\admin\modules\fields\tests\fixtures\ValidatorsFixture;
use Codeception\Test\Unit;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

/**
 * Class ValidatorsTest
 *
 * @package catalogs\modules\admin\modules\fields\tests
 */
class ValidatorsTest extends Unit
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
            'validators' => ValidatorsFixture::class,
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
        $validator = new FieldValidator([
            'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-0')->toString(),
        ]);

        // Empty required fields
        $this->assertFalse($validator->validate(['type']));

        $validator->active = -1;
        $this->assertFalse($validator->validate(['active']));

        $validator->sort = -1;
        $this->assertFalse($validator->validate(['sort']));

        $validator->options = $this->faker->text(500);
        $this->assertFalse($validator->validate(['options']));

        $validator->type = $this->faker->text();
        $this->assertFalse($validator->validate(['type']));

        // Non-unique type
        $validator->type = FieldValidator::TYPE_STRING;
        $this->assertFalse($validator->validate(['type']));
    }

    /**
     * Create test
     */
    public function testCreate()
    {
        $validator = new FieldValidator([
            'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-0')->toString(),
            'type' => FieldValidator::TYPE_REQUIRED,
            'options' => '',
            'active' => 1,
            'sort' => 100
        ]);

        $field = $validator->field;
        $count = count($field->fieldValidators);

        $result = $validator->insert();

        // Test whether field was created.
        $this->assertTrue($result);

        $this->makeSure($validator);
        $field->refresh();

        $this->assertTrue(count($field->fieldValidators) - $count === 1);
    }

    /**
     * Update test
     */
    public function testUpdate()
    {
        $validator = FieldValidator::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'validator-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($validator);

        $validator->type = FieldValidator::TYPE_REQUIRED;
        $result = $validator->update();

        $this->assertNotFalse($result);
    }

    /**
     * Copy test
     */
    public function testCopy()
    {
        $validator = FieldValidator::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'validator-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($validator);

        $field = $validator->field;
        $count = count($field->fieldValidators);

        /* @var FieldValidator $clone */
        $clone = $validator->duplicate();
        $clone->field_uuid = $validator->field_uuid;
        $clone->type = FieldValidator::TYPE_UNIQUE;

        $result = $clone->save();
        $field->refresh();

        $this->assertTrue($result);
        $this->makeSure($clone);
        $this->assertTrue(count($field->fieldValidators) - $count === 1);
    }

    /**
     * Delete test
     */
    public function testDelete()
    {
        $validator = FieldValidator::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'validator-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($validator);

        $field = $validator->field;
        $count = count($field->fieldValidators);

        // Delete user field
        $result = $validator->delete();
        $field->refresh();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($validator->refresh());
        $this->assertTrue($count - count($field->fieldValidators) === 1);
    }

    /**
     * @param FieldValidator $validator
     */
    protected function makeSure($validator)
    {
        $this->assertTrue($validator instanceof FieldValidator);
        $this->assertNotNull($validator->field);
    }
}