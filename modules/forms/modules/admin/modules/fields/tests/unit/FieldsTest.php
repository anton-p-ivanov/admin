<?php

namespace forms\modules\fields\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use Faker\Factory;
use forms\models\Form;
use forms\modules\admin\models\Result;
use forms\modules\admin\modules\fields\models\Field;
use forms\modules\admin\modules\fields\models\FieldValidator;
use forms\modules\admin\modules\fields\models\FieldValue;
use forms\modules\admin\modules\fields\tests\fixtures\FieldValidatorFixture;
use forms\modules\admin\modules\fields\tests\fixtures\FieldValueFixture;
use forms\modules\admin\tests\fixtures\FormFieldFixture;
use forms\modules\admin\tests\fixtures\FormResultFixture;
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
            FormFieldFixture::class,
            FormResultFixture::class,
            FieldValueFixture::class,
            FieldValidatorFixture::class,
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
     * Validate test.
     */
    public function testValidate()
    {
        $field = new Field();

        // Empty required fields
        $this->assertFalse($field->validate(['label']));


        $this->assertTrue($field->validate(['form_uuid']));

        // String field can not be multiple
        $field->type = Field::FIELD_TYPE_DEFAULT;
        $field->multiple = true;
        $this->assertFalse($field->validate(['type']));

        // Unique code
        $field->code = 'FORM_FIELD_0';
        $field->form_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-0')->toString();
        $this->assertFalse($field->validate(['code']));
    }

    /**
     * Create test.
     */
    public function testCreate()
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
     * Update test.
     */
    public function testUpdate()
    {
        $field = Field::findOne(['code' => 'FORM_FIELD_1']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        $field->code = 'FORM_FIELD_UPDATE_1';
        $field->type = Field::FIELD_TYPE_DEFAULT;
        $field->update();

        // we expect that old field code was replaced with new one
        $this->assertTrue((int)Result::find()->where(['like', 'data', 'FORM_FIELD_1'])->andWhere(['form_uuid' => $field->form_uuid])->count() === 0);
        $this->assertTrue((int)Result::find()->where(['like', 'data', 'FORM_FIELD_UPDATE_1'])->andWhere(['form_uuid' => $field->form_uuid])->count() > 0);

        // we expect that all values assigned will be removed
        $this->assertTrue((int)$field->getFieldValues()->count() === 0);
    }

    /**
     * Copy test.
     */
    public function testCopy()
    {
        $field = Field::findOne(['code' => 'FORM_FIELD_1']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        // Creating form field clone
        $clone = $field->duplicate();
        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($clone instanceof Field);
        $this->assertTrue($clone->getWorkflow()->one() instanceof Workflow);
        $this->assertTrue($clone->getForm()->one() instanceof Form);
    }

    /**
     * Delete test.
     */
    public function testDelete()
    {
        $field = Field::findOne(['code' => 'FORM_FIELD_1']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        // Delete user field
        $result = $field->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertNull($field->getWorkflow()->one());
        $this->assertEquals(0, (int) $field->getFieldValues()->count());
        $this->assertEquals(0, (int) $field->getFieldValidators()->count());
        $this->assertEquals(0, (int) $this->getResults($field)->count());
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
        $this->assertEquals(10, (int) $field->getFieldValues()->count());
        $this->assertGreaterThan(0, (int) $field->form->getResults()->count());
        $this->assertEquals(count(FieldValidator::getTypes()), $field->getFieldValidators()->count());
        $this->assertGreaterThan(0, (int) $this->getResults($field)->count());
    }

    /**
     * @param Field $field
     * @return \yii\db\ActiveQuery
     */
    protected function getResults(Field $field)
    {
        return Result::find()->where(['like', 'data', $field->code])->andWhere(['form_uuid' => $field->form_uuid]);
    }
}