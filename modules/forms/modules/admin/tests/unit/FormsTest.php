<?php

namespace forms\modules\admin\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use Faker\Factory;
use forms\modules\admin\models\Form;
use forms\modules\admin\tests\fixtures\FormFieldFixture;
use forms\modules\admin\tests\fixtures\FormFixture;
use forms\modules\admin\tests\fixtures\FormResultFixture;
use forms\modules\admin\tests\fixtures\FormStatusFixture;

/**
 * Class FormsTest
 *
 * @package forms\modules\admin\tests
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
            FormFixture::className(),
            FormStatusFixture::className(),
            FormFieldFixture::className(),
            FormResultFixture::className(),
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
     * Create test.
     */
    public function testCreate()
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
    }

    /**
     * Update test.
     */
    public function testUpdate()
    {
        $form = Form::findOne(['code' => 'FORM_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($form);

        $form->code = 'FORM_0_UPDATED';
        $result = $form->save();

        $this->assertTrue($result);
    }

    /**
     * Copy test.
     */
    public function testCopy()
    {
        $form = Form::findOne(['code' => 'FORM_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($form);

        // Creating form field clone
        $clone = $form->duplicate();
        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($clone instanceof Form);
        $this->assertTrue($clone->workflow instanceof Workflow);
    }

    /**
     * Delete test.
     */
    public function testDelete()
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
        $this->assertEquals(0, (int)$form->getFields()->count());
        $this->assertEquals(0, (int)$form->getResults()->count());
        $this->assertEquals(0, (int)$form->getStatuses()->count());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Form $form
     */
    protected function makeSure($form)
    {
        $this->assertTrue($form instanceof Form);
        $this->assertTrue($form->workflow instanceof Workflow);
        $this->assertGreaterThan(0, (int)$form->getFields()->count());
        $this->assertGreaterThan(0, (int)$form->getResults()->count());
        $this->assertGreaterThan(0, (int)$form->getStatuses()->count());
    }
}