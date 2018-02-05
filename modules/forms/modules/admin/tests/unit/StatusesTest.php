<?php

namespace forms\modules\admin\modules\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use Faker\Factory;
use forms\modules\admin\models\Form;
use forms\modules\admin\models\FormStatus;
use forms\modules\admin\tests\fixtures\FormStatusFixture;
use Ramsey\Uuid\Uuid;

/**
 * Class StatusesTest
 *
 * @package forms\modules\admin\modules\tests
 */
class StatusesTest extends Unit
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
            FormStatusFixture::className(),
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
        $status = new FormStatus();

        // Empty required fields
        $this->assertFalse($status->validate(['title']));

        // Non-unique code
        $status->title = $this->faker->text(500);
        $this->assertFalse($status->validate(['title']));

        $status->active = -1;
        $status->default = -1;
        $this->assertFalse($status->validate(['active']));
        $this->assertFalse($status->validate(['default']));

        $status->form_uuid = Uuid::uuid4()->toString();
        $this->assertFalse($status->validate(['form_uuid']));

        $status->sort = -1;
        $this->assertFalse($status->validate(['sort']));
    }

    /**
     * Create test.
     */
    public function testCreate()
    {
        $status = new FormStatus([
            'title' => $this->faker->text(),
            'description' => $this->faker->text(500),
            'active' => 1,
            'default' => 1,
            'sort' => 100,
            'form_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-0')->toString()
        ]);

        $result = $status->insert();

        // Test whether field was created.
        $this->assertTrue($result);

        // Test whether field has a valid workflow record.
        $this->makeSure($status);

        $this->assertTrue($status->isDefault());
        $this->assertCount(1, FormStatus::find()->where(['default' => 1])->all());
    }

    /**
     * Update test.
     */
    public function testUpdate()
    {
        $status = FormStatus::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'status-1')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($status);

        $status->default = 1;
        $result = $status->save();

        $this->assertTrue($result);

        // check default status (can be only one)
        $this->assertTrue($status->isDefault());

        $this->assertCount(1, FormStatus::find()->where(['default' => 1])->all());
    }

    /**
     * Copy test.
     */
    public function testCopy()
    {
        $status = FormStatus::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'status-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($status);

        // Creating form field clone
        $clone = $status->duplicate();
        $result = $clone->save();

        $status->refresh();

        $this->assertTrue($result);
        $this->makeSure($clone);

        // check default status (can be only one)
        $this->assertFalse($status->isDefault());
        $this->assertTrue($clone->isDefault());

        $this->assertEquals(1, (int) FormStatus::find()->where(['default' => true])->count());
    }

    /**
     * Delete test.
     */
    public function testDelete()
    {
        $status = FormStatus::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'status-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($status);

        // Delete user field
        $result = $status->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($status->refresh());
        $this->assertNull($status->getWorkflow()->one());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param FormStatus $status
     */
    protected function makeSure($status)
    {
        $this->assertTrue($status instanceof FormStatus);
        $this->assertTrue($status->workflow instanceof Workflow);
        $this->assertTrue($status->form instanceof Form);
    }
}