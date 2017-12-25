<?php

namespace accounts\modules\fields\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use accounts\models\AccountData;
use accounts\modules\fields\tests\fixtures\FieldValidatorFixture;
use accounts\modules\fields\tests\fixtures\FieldValueFixture;
use accounts\modules\fields\tests\fixtures\AccountDataFixture;
use accounts\modules\fields\models\Field;
use accounts\modules\fields\models\FieldValidator;
use accounts\modules\fields\models\FieldValue;
use Yii;
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
            'field_values' => FieldValueFixture::className(),
            'field_validators' => FieldValidatorFixture::className(),
            'user_data' => AccountDataFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        Yii::setAlias('@accounts', '@app/modules/accounts');
    }

    /**
     * Testing some user field specific validations.
     */
    public function testFieldValidate()
    {
        $field = new Field();

        // String field can not be multiple
        $field->type = Field::FIELD_TYPE_DEFAULT;
        $field->multiple = true;
        $this->assertFalse($field->validate(['type']));

        // Multiple fields must have a values assigned
        $field->type = Field::FIELD_TYPE_LIST;
        $field->multiple = true;
        $this->assertFalse($field->validate(['type']));

        // Unique code
        $field->code = 'ACCOUNT_FIELD_TEST_01';
        $this->assertFalse($field->validate(['code']));
    }

    /**
     * Testing user field creation.
     */
    public function testFieldCreate()
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
            $random = Yii::$app->security->generateRandomString(6);
            (new FieldValue([
                'field_uuid' => $field->uuid,
                'value' => 'test value ' . $random,
                'label' => 'test label' . $random
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
     * Testing updating field attributes
     */
    public function testFieldUpdate()
    {
        $field = Field::findOne(['code' => 'ACCOUNT_FIELD_TEST_01']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);


        $field->code = 'ACCOUNT_FIELD_TEST_UPDATE_01';
        $field->type = Field::FIELD_TYPE_DEFAULT;
        $field->update();

        // we expect that old field code was replaced with new one
        $this->assertTrue((int)AccountData::find()->where(['like', 'data', 'ACCOUNT_FIELD_TEST_01'])->count() === 0);
        $this->assertTrue((int)AccountData::find()->where(['like', 'data', 'ACCOUNT_FIELD_TEST_UPDATE_01'])->count() > 0);

        // we expect that all values assigned will be removed
        $this->assertTrue((int)$field->getFieldValues()->count() === 0);
    }

    /**
     * Testing copying of existing user field with all related data
     */
    public function testFieldDeepCopy()
    {
        $field = Field::findOne(['code' => 'ACCOUNT_FIELD_TEST_01']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($field);

        // Creating user field clone
        $clone = $field->duplicate(true);

        $this->assertTrue($clone instanceof Field);
        $this->assertTrue($clone->getWorkflow()->one() instanceof Workflow);
        $this->assertTrue((int)$clone->getFieldValues()->count() === 10);
        $this->assertTrue((int)$clone->getFieldValidators()->count() === count(FieldValidator::getTypes()));
    }

    /**
     * Testing user field deletion with all related records.
     */
    public function testFieldDelete()
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
        $this->assertTrue((int)AccountData::find()->where(['like', 'data', $field->code])->count() === 0);
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
        $this->assertTrue((int)AccountData::find()->where(['like', 'data', $field->code])->count() > 0);
    }
}