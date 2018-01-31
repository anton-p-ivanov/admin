<?php

namespace catalogs\modules\admin\modules\fields\tests;

use app\models\Workflow;
use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\modules\fields\models\Field;
use catalogs\modules\admin\modules\fields\models\FieldValidator;
use catalogs\modules\admin\modules\fields\models\FieldValue;
use catalogs\modules\admin\modules\fields\models\Group;
use catalogs\modules\admin\modules\fields\tests\fixtures\FieldFixture;
use catalogs\modules\admin\modules\fields\tests\fixtures\ValidatorsFixture;
use catalogs\modules\admin\modules\fields\tests\fixtures\ValueFixture;
use Codeception\Test\Unit;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

/**
 * Class FieldsTest
 *
 * @package catalogs\modules\admin\modules\fields\tests
 */
class FieldsTest extends Unit
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
            'fields' => FieldFixture::className(),
            'validators' => ValidatorsFixture::className(),
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
        $field = new Field();

        // Empty required fields
        $this->assertFalse($field->validate(['type']));
        $this->assertFalse($field->validate(['label']));
        $this->assertFalse($field->validate(['code']));

        $field->active = -1;
        $this->assertFalse($field->validate(['active']));

        $field->multiple = -1;
        $this->assertFalse($field->validate(['multiple']));

        $field->list = -1;
        $this->assertFalse($field->validate(['list']));

        $field->code = $this->faker->text();
        $this->assertFalse($field->validate(['code']));

        $field->sort = -1;
        $this->assertFalse($field->validate(['sort']));

        $field->group_uuid = $this->faker->text();
        $this->assertFalse($field->validate(['group_uuid']));
    }

    /**
     * Create test
     */
    public function testCreate()
    {
        $field = new Field([
            'label' => $this->faker->text(),
            'description' => $this->faker->text(500),
            'code' => 'CATALOG_FIELD_CREATE',
            'type' => Field::FIELD_TYPE_DEFAULT,
            'multiple' => 0,
            'default' => '',
            'options' => '',
            'active' => 1,
            'list' => 0,
            'sort' => 100,
            'catalog_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'catalog-0')->toString(),
            'group_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'group-0')->toString(),
        ]);

        $result = $field->insert();

        // Test whether field was created.
        $this->assertTrue($result);
        $this->assertTrue($field instanceof Field);
        $this->assertTrue($field->catalog instanceof Catalog);
        $this->assertTrue($field->group instanceof Group);
        $this->assertTrue($field->workflow instanceof Workflow);
    }

    /**
     * Update test
     */
    public function testUpdate()
    {
        $field = Field::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        $field->type = Field::FIELD_TYPE_STRING;
        $field->multiple = false;
        $result = $field->update();

        $this->assertNotFalse($result);
        $this->assertTrue((int) $field->getFieldValidators()->count() > 0);
        $this->assertTrue((int) $field->getFieldValues()->count() == 0);
    }

    /**
     * Copy test
     */
    public function testCopy()
    {
        $field = Field::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        /* @var Field $clone */
        $clone = $field->duplicate();
        $clone->catalog_uuid = $field->catalog_uuid;

        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($clone instanceof Field);
        $this->assertTrue($clone->workflow instanceof Workflow);
    }

    /**
     * Delete test
     */
    public function testDelete()
    {
        $field = Field::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        $result = $field->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($field->refresh());

        $this->assertCount(0, Workflow::findAll(['uuid' => $field->workflow_uuid]));
        $this->assertCount(0, FieldValidator::findAll(['field_uuid' => $field->uuid]));
        $this->assertCount(0, FieldValue::findAll(['field_uuid' => $field->uuid]));
    }

    /**
     * @param Field $field
     */
    protected function makeSure($field)
    {
        $this->assertTrue($field instanceof Field);
        $this->assertTrue($field->workflow instanceof Workflow);
        $this->assertTrue((int) $field->getFieldValidators()->count() > 0);
        $this->assertTrue((int) $field->getFieldValues()->count() > 0);
    }
}