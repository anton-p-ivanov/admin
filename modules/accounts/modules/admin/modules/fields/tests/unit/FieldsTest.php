<?php

namespace accounts\modules\fields\tests;

use accounts\modules\admin\modules\fields\models\FieldValue;
use app\models\Workflow;
use Codeception\Test\Unit;
use accounts\models\AccountProperty;
use accounts\modules\admin\modules\fields\tests\fixtures\FieldValidatorFixture;
use accounts\modules\admin\modules\fields\tests\fixtures\FieldValueFixture;
use accounts\modules\admin\modules\fields\tests\fixtures\AccountDataFixture;
use accounts\modules\admin\modules\fields\models\Field;
use accounts\modules\admin\modules\fields\models\FieldValidator;
use yii\helpers\Inflector;

/**
 * Class FieldsTest
 * @package fields\tests
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
            FieldValueFixture::class,
            FieldValidatorFixture::class,
            AccountDataFixture::class,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@accounts', '@app/modules/accounts');
    }

    /**
     * Validation test.
     */
    public function testFieldValidate()
    {
        $field = new Field();

        // String field can not be multiple
        $field->type = Field::FIELD_TYPE_DEFAULT;
        $field->multiple = true;
        $this->assertFalse($field->validate(['type']));

        // Unique code
        $field->code = 'ACCOUNT_FIELD_TEST_01';
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
            'multiple' => false
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
        $field = Field::findOne(['code' => 'ACCOUNT_FIELD_TEST_01']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        $field->code = 'ACCOUNT_FIELD_TEST_UPDATE_01';
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
        $field = Field::findOne(['code' => 'ACCOUNT_FIELD_TEST_01']);

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
        $field = Field::findOne(['code' => 'ACCOUNT_FIELD_TEST_01']);

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
        $this->assertTrue((int)AccountProperty::find()->where(['field_uuid' => $field->uuid])->count() === 0);
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
        $this->assertTrue((int)AccountProperty::find()->where(['field_uuid' => $field->uuid])->count() > 0);
    }
}