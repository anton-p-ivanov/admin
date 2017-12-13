<?php

namespace forms\modules\fields\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use Faker\Factory;
use forms\models\Form;
use forms\models\FormResult;
use forms\modules\fields\models\Field;
use forms\modules\fields\models\FieldValidator;
use forms\modules\fields\models\FieldValue;
use forms\modules\fields\tests\fixtures\FieldValidatorFixture;
use forms\modules\fields\tests\fixtures\FieldValueFixture;
use forms\modules\fields\tests\fixtures\FormFixture;
use forms\modules\fields\tests\fixtures\FormResultFixture;
use Ramsey\Uuid\Uuid;
use yii\helpers\Inflector;

/**
 * Class FieldsTest
 * @package forms\modules\fields\tests
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
            'forms' => FormFixture::className(),
            'field_values' => FieldValueFixture::className(),
            'field_validators' => FieldValidatorFixture::className(),
            'forms_results' => FormResultFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@forms', '@app/modules/forms');

        $this->faker = Factory::create();
    }

    /**
     * Testing some user field specific validations.
     */
    public function testFieldValidate()
    {
        $field = new Field();

        // Empty required fields
        $this->assertFalse($field->validate(['form_uuid']));
        $this->assertFalse($field->validate(['label']));

        $field->form_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-0')->toString();
        $this->assertTrue($field->validate(['form_uuid']));

        // String field can not be multiple
        $field->type = Field::FIELD_TYPE_DEFAULT;
        $field->multiple = true;
        $this->assertFalse($field->validate(['type']));

        // Multiple fields must have a values assigned
        $field->type = Field::FIELD_TYPE_LIST;
        $field->multiple = true;
        $this->assertFalse($field->validate(['type']));

        // Unique code
        $field->code = 'FORM_FIELD_0';
        $this->assertFalse($field->validate(['code']));
    }

    /**
     * Testing field creation.
     */
    public function testFieldCreate()
    {
        $field = new Field([
            'label' => $this->faker->text(50),
            'type' => Field::FIELD_TYPE_DEFAULT,
            'multiple' => false,
            'form_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-0')->toString()
        ]);

        $result = $field->insert();

        // Test whether field was created.
        $this->assertTrue($result);

        // Test whether field has a valid workflow record.
        $this->assertTrue($field->getWorkflow()->one() instanceof Workflow);

        // Test whether field has a form relation.
        $this->assertTrue($field->getForm()->one() instanceof Form);

        // Test valid code generation.
        $this->assertTrue($field->code === mb_strtoupper(Inflector::slug($field->label)));

        $field->multiple = true;
        $field->type = Field::FIELD_TYPE_LIST;

        for ($i = 0; $i < 10; $i++) {
            (new FieldValue([
                'field_uuid' => $field->uuid,
                'value' => $this->faker->text(),
                'label' => $this->faker->text()
            ]))->insert();
        }

        // Test count of created field values
        $this->assertEquals(10, (int)$field->getFieldValues()->count());

        foreach (FieldValidator::getTypes() as $type => $name) {
            (new FieldValidator([
                'field_uuid' => $field->uuid,
                'type' => $type,
                'active' => true
            ]))->insert();
        }

        // Test count of created field validators
        $this->assertEquals(count(FieldValidator::getTypes()), (int)$field->getFieldValidators()->count());
    }

    /**
     * Testing updating field attributes
     */
    public function testFieldUpdate()
    {
        $field = Field::findOne(['code' => 'FORM_FIELD_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        $field->code = 'FORM_FIELD_UPDATE_0';
        $field->type = Field::FIELD_TYPE_DEFAULT;
        $field->update();

        // we expect that old field code was replaced with new one
        $this->assertTrue((int)FormResult::find()->where(['like', 'data', 'FORM_FIELD_0'])->andWhere(['form_uuid' => $field->form_uuid])->count() === 0);
        $this->assertTrue((int)FormResult::find()->where(['like', 'data', 'FORM_FIELD_UPDATE_0'])->andWhere(['form_uuid' => $field->form_uuid])->count() > 0);

        // we expect that all values assigned will be removed
        $this->assertTrue((int)$field->getFieldValues()->count() === 0);
    }

    /**
     * Testing copying of existing form field with all related data
     */
    public function testFieldCopy()
    {
        $field = Field::findOne(['code' => 'FORM_FIELD_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        // Creating form field clone
        $clone = $field->duplicate();

        $this->assertTrue($clone instanceof Field);
        $this->assertTrue($clone->getWorkflow()->one() instanceof Workflow);
        $this->assertTrue($clone->getFieldValues()->count() === $field->getFieldValues()->count());
        $this->assertTrue($clone->getFieldValidators()->count() === $field->getFieldValidators()->count());
    }

    /**
     * Testing form field deletion with all related records.
     */
    public function testFieldDelete()
    {
        $field = Field::findOne(['code' => 'FORM_FIELD_0']);

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
        $this->assertTrue((int)FormResult::find()->where(['like', 'data', $field->code])->andWhere(['form_uuid' => $field->form_uuid])->count() === 0);
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Field $field
     */
    protected function makeSure($field)
    {
        $this->assertTrue($field instanceof Field);
        $this->assertTrue($field->workflow instanceof Workflow);
        $this->assertTrue($field->form instanceof Form);
        $this->assertCount(10, $field->fieldValues);
        $this->assertCount(1, $field->form->results);
        $this->assertCount(count(FieldValidator::getTypes()), $field->fieldValidators);
        $this->assertTrue((int)FormResult::find()->where(['like', 'data', $field->code])->andWhere(['form_uuid' => $field->form_uuid])->count() > 0);
    }
}