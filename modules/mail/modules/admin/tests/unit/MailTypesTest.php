<?php

namespace mail\modules\admin\tests;

use app\models\Workflow;
use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use mail\modules\admin\models\Type;
use yii\helpers\Inflector;

/**
 * Class MailTypesTest
 *
 * @package mail\modules\admin\tests
 */
class MailTypesTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'types' => 'mail\modules\admin\tests\fixtures\MailTypeFixture'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@mail', '@app/modules/mail');

        $this->faker = Factory::create();
    }

    /**
     * Testing some mail type specific validations.
     */
    public function testTypeValidate()
    {
        $type = new Type();

        // empty title
        $type->title = null;
        $this->assertFalse($type->validate(['title']));

        // too long title
        $type->title = \Yii::$app->security->generateRandomString(300);
        $this->assertFalse($type->validate(['title']));

        // non-unique code
        $type->code = 'MAIL_TYPE_0';
        $this->assertFalse($type->validate(['code']));

        // valid attributes
        $type->title = 'Validate test mail type';
        $type->code = null;
        $this->assertTrue($type->validate());
    }

    /**
     * Testing mail type creation.
     */
    public function testTypeCreate()
    {
        $type = new Type([
            'title' => $this->faker->text(50),
            'description' => ''
        ]);

        $result = $type->insert();

        // Test whether mail type was created.
        $this->assertTrue($result);

        // Test whether field has a valid workflow record.
        $this->assertTrue($type->getWorkflow()->one() instanceof Workflow);

        // Test valid code generation.
        $this->assertTrue($type->code === mb_strtoupper(Inflector::slug($type->title)));
    }

    /**
     * Testing updating field attributes.
     */
    public function testTypeUpdate()
    {
        $type = Type::findOne(['code' => 'MAIL_TYPE_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($type);

        $type->code = null;
        $type->title = 'Mail type 1 (updated)';
        $type->description = 'Description for mail type 1 (updated)';
        $result = $type->update();

        $type->refresh();

        $this->assertTrue($result > 0);
        $this->assertTrue($type->code === mb_strtoupper(Inflector::slug($type->title)));
    }

    /**
     * Testing copying of mail type.
     */
    public function testTypeCopy()
    {
        $type = Type::findOne(['code' => 'MAIL_TYPE_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($type);

        // Creating user field clone
        $clone = $type->duplicate();
        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($clone->getWorkflow()->one() instanceof Workflow);
    }

    /**
     * Testing mail type deletion with all related records.
     */
    public function testTypeDelete()
    {
        $type = Type::findOne(['code' => 'MAIL_TYPE_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($type);

        // Delete user field
        $result = $type->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($type->refresh());
        $this->assertNull($type->getWorkflow()->one());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param $field
     */
    protected function makeSure($field)
    {
        $this->assertTrue($field instanceof Type);
        $this->assertTrue($field->workflow instanceof Workflow);
    }
}