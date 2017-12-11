<?php

namespace users\modules\fields\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use users\models\UserData;
use users\modules\fields\tests\fixtures\FieldValidatorFixture;
use users\modules\fields\tests\fixtures\FieldValueFixture;
use users\modules\fields\tests\fixtures\UserDataFixture;
use users\modules\fields\models\Field;
use users\modules\fields\models\FieldValidator;
use users\modules\fields\models\FieldValue;
use Yii;

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
            'user_data' => UserDataFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        Yii::setAlias('@users', '@app/modules/users');
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
        $field->code = 'USER_FIELD_TEST_01';
        $this->assertFalse($field->validate(['code']));
    }

    /**
     * Testing user field creation.
     */
    public function testFieldInsert()
    {
        $field = new Field([
            'label' => 'Test field ' . Yii::$app->security->generateRandomString(6),
            'type' => Field::FIELD_TYPE_DEFAULT,
            'multiple' => false
        ]);

        $result = $field->insert();

        $this->assertTrue($result);
        $this->assertTrue($field->getWorkflow()->one() instanceof Workflow);

        $field->type = Field::FIELD_TYPE_LIST;
        $field->multiple = true;

        for ($i = 0; $i < 10; $i++) {
            $random = Yii::$app->security->generateRandomString(6);
            (new FieldValue([
                'field_uuid' => $field->uuid,
                'value' => 'test value ' . $random,
                'label' => 'test label' . $random
            ]))->insert();
        }

        $this->assertTrue((int)$field->getFieldValues()->count() === 10);

        foreach (FieldValidator::getTypes() as $type => $name) {
            (new FieldValidator([
                'field_uuid' => $field->uuid,
                'type' => $type,
                'active' => true
            ]))->insert();
        }

        $this->assertTrue(count(FieldValidator::getTypes()) === (int)$field->getFieldValidators()->count());
    }

    /**
     * Testing copying of existing user field with all related data
     */
    public function testFieldDeepCopy()
    {
        $field = Field::findOne(['code' => 'USER_FIELD_TEST_01']);

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
        $this->assertTrue((int)UserData::find()->where(['like', 'data', $field->code])->count() === 0);
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
        $this->assertTrue((int)UserData::find()->where(['like', 'data', $field->code])->count() > 0);
    }
}