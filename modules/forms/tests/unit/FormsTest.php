<?php

namespace forms\modules\fields\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use Faker\Factory;
use forms\models\Form;
use forms\tests\fixtures\FormEventFixture;
use forms\tests\fixtures\FormFieldFixture;
use forms\tests\fixtures\FormFixture;
use forms\tests\fixtures\FormResultFixture;
use mail\models\Type;
use Ramsey\Uuid\Uuid;

/**
 * Class FormsTest
 * @package forms\modules\fields\tests
 */
class FormsTest extends Unit
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
            'forms_events' => FormEventFixture::className(),
            'forms_fields' => FormFieldFixture::className(),
            'forms_results' => FormResultFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@mail', '@app/modules/mail');
        \Yii::setAlias('@forms', '@app/modules/forms');

        $this->faker = Factory::create();
    }

    /**
     * Testing some user field specific validations.
     */
    public function testFieldValidate()
    {
        $form = new Form();

        // Empty required fields
        $this->assertFalse($form->validate(['title']));
        $this->assertFalse($form->validate(['code']));

        // Non-unique code
        $form->code = 'FORM_0';
        $this->assertFalse($form->validate(['code']));

        // Invalid field code in template
        $form->template = '{{INVALID_FIELD_CODE}}';
        $this->assertFalse($form->validate(['template']));

        // Second date is larger than first one
        $form->active_dates = [
            'active_from_date' => $this->faker->dateTimeInInterval($startDate = 'now', $interval = '+ 5 days')->format(\Yii::$app->formatter->datetimeFormat),
            'active_to_date' => $this->faker->dateTimeInInterval($startDate = 'now', $interval = '- 5 days')->format(\Yii::$app->formatter->datetimeFormat)
        ];

        $this->assertFalse($form->validate(['active_dates']));
    }
    
    /**
     * Testing form creation.
     */
    public function testFormCreate()
    {
        $form = new Form([
            'title' => $this->faker->text(),
            'description' => $this->faker->text(),
            'code' => 'FORM_CREATE_TEST'
        ]);

        $result = $form->insert();

        // Test whether field was created.
        $this->assertTrue($result);

        // Test whether field has a valid workflow record.
        $this->assertTrue($form->getWorkflow()->one() instanceof Workflow);

        // Test whether field has a form relation.
        $this->assertTrue($form->getEventRelation()->one() instanceof Type);
    }

    /**
     * Testing updating field attributes
     */
    public function testFormUpdate()
    {
        $form = Form::findOne(['code' => 'FORM_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($form);

        $form->setEvent(Uuid::uuid3(Uuid::NAMESPACE_URL, 'mail-type-1')->toString());
        $result = $form->save();

        $this->assertTrue($result);

        // we expect that all values assigned will be removed
        $this->assertTrue($form->getEventRelation()->one()->{'code'} === 'MAIL_TYPE_1');
    }
    
    /**
     * Testing copying of form with all related data
     */
    public function testFormCopy()
    {
        $form = Form::findOne(['code' => 'FORM_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($form);

        // Creating form field clone
        $clone = $form->duplicate(true);

        $this->assertTrue($clone instanceof Form);
        $this->assertTrue($clone->getWorkflow()->one() instanceof Workflow);
        $this->assertTrue($clone->getFields()->count() === $form->getFields()->count());
        $this->assertTrue($clone->getStatuses()->count() === $form->getStatuses()->count());
    }

    /**
     * Testing form deletion with all related records.
     */
    public function testFormDelete()
    {
        $form = Form::findOne(['code' => 'FORM_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($form);

        // Delete user field
        $result = $form->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($form->refresh());
        $this->assertNull($form->getWorkflow()->one());
        $this->assertNull($form->getEventRelation()->one());
        $this->assertTrue((int)$form->getFields()->count() === 0);
        $this->assertTrue((int)$form->getResults()->count() === 0);
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Form $form
     */
    protected function makeSure($form)
    {
        $this->assertTrue($form instanceof Form);
        $this->assertTrue($form->workflow instanceof Workflow);
        $this->assertTrue($form->getEvent() instanceof Type);
        $this->assertTrue((int)$form->getFields()->count() > 0);
        $this->assertTrue((int)$form->getResults()->count() > 0);
    }
}