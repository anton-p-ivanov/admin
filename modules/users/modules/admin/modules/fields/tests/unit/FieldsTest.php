<?php

namespace users\modules\admin\modules\fields\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use users\models\UserProperty;
use users\modules\admin\modules\fields\tests\fixtures\FieldValidatorFixture;
use users\modules\admin\modules\fields\tests\fixtures\FieldValueFixture;
use users\modules\admin\modules\fields\tests\fixtures\UserDataFixture;
use users\modules\admin\modules\fields\models\Field;
use users\modules\admin\modules\fields\models\FieldValidator;
use users\modules\admin\modules\fields\models\FieldValue;
use yii\helpers\Inflector;

/**
 * Class FieldsTest
 *
 * @package users\modules\admin\modules\fields\tests
 */
class FieldsTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'field_values' => FieldValueFixture::class,
            'field_validators' => FieldValidatorFixture::class,
            'user_data' => UserDataFixture::class,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@users', '@app/modules/users');
    }

    /**
     * Testing some user field specific validations.
     */
    public function testValidate()
    {
        $field = new Field();

        // String field can not be multiple
        $field->type = Field::FIELD_TYPE_DEFAULT;
        $field->multiple = true;
        $this->assertFalse($field->validate(['type']));

        // Unique code
        $field->code = 'USER_FIELD_TEST_01';
        $this->assertFalse($field->validate(['code']));
    }

    /**
     * Create test.
     */
    public function testCreate()
    {
        $field = new Field([
            'label' => 'Test field ' . date('YmdHis'),
            'type' => Field::FIELD_TYPE_DEFAULT,
            'multiple' => false,
            'default' => ''
        ]);

        $result = $field->insert();

        // Test whether field was created.
        $this->assertTrue($result);

        // Test whether field has a valid workflow record.
        $this->assertTrue($field->getWorkflow()->one() instanceof Workflow);

        // Test valid code generation.
        $this->assertTrue($field->code === mb_strtoupper(Inflector::slug($field->label)));

        $field->multiple = true;
        $field->type = Field::FIELD_TYPE_LIST;

        for ($i = 0; $i < 10; $i++) {
            (new FieldValue([
                'field_uuid' => $field->uuid,
                'value' => 'test value ' . $i,
                'label' => 'test label' . $i
            ]))->insert();
        }

        // Test count of created field values
        $this->assertTrue((int)$field->getFieldValues()->count() === 10);

        foreach (FieldValidator::getTypes() as $type => $name) {
            (new FieldValidator([
                'field_uuid' => $field->uuid,
                'type' => $type,
                'active' => true
            ]))->insert();
        }

        // Test count of created field validators
        $this->assertTrue(count(FieldValidator::getTypes()) === (int)$field->getFieldValidators()->count());
    }

    /**
     * Update test.
     */
    public function testUpdate()
    {
        $field = Field::findOne(['code' => 'USER_FIELD_TEST_01']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        $field->code = 'USER_FIELD_TEST_UPDATE_01';
        $field->type = Field::FIELD_TYPE_DEFAULT;
        $field->update();

        // we expect that all values assigned will be removed
        $this->assertTrue((int)$field->getFieldValues()->count() === 0);
    }

    /**
     * Copy test.
     */
    public function testCopy()
    {
        $field = Field::findOne(['code' => 'USER_FIELD_TEST_01']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        // Creating user field clone
        $clone = $field->duplicate();
        $clone->save();

        $this->assertTrue($clone instanceof Field);
        $this->assertTrue($clone->getWorkflow()->one() instanceof Workflow);
    }

    /**
     * Delete test.
     */
    public function testDelete()
    {
        $field = Field::findOne(['code' => 'USER_FIELD_TEST_01']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        // Delete user field
        $result = $field->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($field->refresh());
        $this->assertNull($field->getWorkflow()->one());
        $this->assertTrue((int)$field->getFieldValues()->count() === 0);
        $this->assertTrue((int)$field->getFieldValidators()->count() === 0);
        $this->assertTrue((int)UserProperty::find()->where(['field_uuid' => $field->uuid])->count() === 0);
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param $field
     */
    protected function makeSure($field)
    {
        $this->assertTrue($field instanceof Field);
        $this->assertTrue($field->workflow instanceof Workflow);
        $this->assertCount(10, $field->fieldValues);
        $this->assertCount(count(FieldValidator::getTypes()), $field->fieldValidators);
        $this->assertTrue((int)UserProperty::find()->where(['field_uuid' => $field->uuid])->count() > 0);
    }
}