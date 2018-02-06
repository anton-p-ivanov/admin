<?php

namespace accounts\modules\admin\modules\fields\tests;

use accounts\modules\admin\modules\fields\models\FieldValue;
use accounts\modules\admin\modules\fields\models\Field;
use accounts\modules\admin\modules\fields\tests\fixtures\FieldValueFixture;
use Codeception\Test\Unit;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

/**
 * Class ValuesTest
 *
 * @package accounts\modules\admin\modules\fields\tests
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
            FieldValueFixture::class,
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
        $value = new FieldValue();

        $value->sort = -1;
        $this->assertFalse($value->validate(['sort']));

        $value->label = $this->faker->text(500);
        $this->assertFalse($value->validate(['label']));
    }

    /**
     * Create test.
     */
    public function testCreate()
    {
        $value = new FieldValue([
            'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-0')->toString(),
            'label' => $this->faker->text(),
            'value' => $this->faker->text(),
        ]);

        $result = $value->insert();

        // Test whether field was created.
        $this->assertTrue($result);

        // Test whether field has a valid workflow record.
        $this->makeSure($value);
    }

    /**
     * Update test.
     */
    public function testUpdate()
    {
        $value = FieldValue::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-value-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($value);

        $value->sort = 200;
        $result = $value->update();

        $this->assertTrue($result === 1);
    }

    /**
     * Copy test.
     */
    public function testCopy()
    {
        $value = FieldValue::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-value-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($value);

        // Creating user field clone
        $clone = $value->duplicate();
        $clone->save();

        $this->makeSure($clone);
    }

    /**
     * Delete test.
     */
    public function testDelete()
    {
        $value = FieldValue::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-value-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($value);

        // Delete user field
        $result = $value->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param $value
     */
    protected function makeSure($value)
    {
        $this->assertTrue($value instanceof FieldValue);
        $this->assertTrue($value->field instanceof Field);
    }
}