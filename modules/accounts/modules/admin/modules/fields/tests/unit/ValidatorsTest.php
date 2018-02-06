<?php

namespace accounts\modules\admin\modules\fields\tests;

use Codeception\Test\Unit;
use accounts\modules\admin\modules\fields\tests\fixtures\FieldValidatorFixture;
use accounts\modules\admin\modules\fields\models\Field;
use accounts\modules\admin\modules\fields\models\FieldValidator;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

/**
 * Class ValidatorsTest
 *
 * @package accounts\modules\admin\modules\fields\tests
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
            FieldValidatorFixture::class,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        $this->faker = Factory::create();

        \Yii::setAlias('@accounts', '@app/modules/accounts');
    }

    /**
     * Validation test.
     */
    public function testValidate()
    {
        $validator = new FieldValidator();

        $validator->type = null;
        $this->assertFalse($validator->validate(['type']));

        $validator->type = FieldValidator::TYPE_STRING;
        $validator->field_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-1')->toString();
        $this->assertFalse($validator->validate(['type']));

        $validator->active = -1;
        $this->assertFalse($validator->validate(['active']));

        $validator->sort = -1;
        $this->assertFalse($validator->validate(['sort']));

        $validator->options = $this->faker->text();
        $this->assertFalse($validator->validate(['options']));
    }

    /**
     * Create test.
     */
    public function testCreate()
    {
        $validator = new FieldValidator([
            'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-0')->toString(),
            'type' => FieldValidator::TYPE_UNIQUE,
        ]);

        $result = $validator->insert();

        // Test whether field was created.
        $this->assertTrue($result);

        // Test whether field has a valid workflow record.
        $this->makeSure($validator);
    }

    /**
     * Update test.
     */
    public function testUpdate()
    {
        $validator = FieldValidator::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-validator-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($validator);

        $validator->sort = 200;
        $result = $validator->update();

        $this->assertTrue($result === 1);
    }

    /**
     * Copy test.
     */
    public function testCopy()
    {
        $validator = FieldValidator::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-validator-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($validator);

        // Creating user field clone
        $clone = $validator->duplicate();
        $clone->type = FieldValidator::TYPE_UNIQUE;
        $clone->save();

        $this->makeSure($clone);
    }

    /**
     * Delete test.
     */
    public function testDelete()
    {
        $validator = FieldValidator::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-validator-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($validator);

        // Delete user field
        $result = $validator->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param $validator
     */
    protected function makeSure($validator)
    {
        $this->assertTrue($validator instanceof FieldValidator);
        $this->assertTrue($validator->field instanceof Field);
    }
}