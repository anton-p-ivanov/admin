<?php

namespace mail\modules\admin\tests;

use app\models\Site;
use app\models\Workflow;
use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use mail\modules\admin\models\Template;
use mail\models\Type;

/**
 * Class MailTemplateTest
 *
 * @package mail\modules\admin\tests
 */
class MailTemplateTest extends Unit
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
            'templates' => 'mail\modules\admin\tests\fixtures\MailTemplateFixture',
            'templates_types' => 'mail\modules\admin\tests\fixtures\MailTemplateTypeFixture',
            'templates_sites' => 'mail\modules\admin\tests\fixtures\MailTemplateSiteFixture',
        ];
    }

    protected function _before()
    {
        \Yii::setAlias('@mail', '@app/modules/mail');

        $this->faker = Factory::create();
    }

    /**
     * Testing some mail template specific validations.
     */
    public function testTemplateValidate()
    {
        $template = new Template();

        // empty required fields
        $template->subject = null;
        $template->to = null;
        $template->type = null;

        $this->assertFalse($template->validate(['subject']));
        $this->assertFalse($template->validate(['to']));
        $this->assertFalse($template->validate(['type']));

        // invalid email fields
        $template->from = $this->faker->text();
        $this->assertFalse($template->validate(['from']));

        // non unique code
        $template->code = 'MAIL_TEMPLATE_0';
        $this->assertFalse($template->validate(['code']));

        // too long fields
        $template->subject = \Yii::$app->security->generateRandomString(300);
        $this->assertFalse($template->validate(['subject']));
    }
    
    /**
     * Testing mail type creation.
     */
    public function testTemplateCreate()
    {
        $template = new Template([
            'code' => 'TEMPLATE_CREATE_TEST',
            'from' => $this->faker->email,
            'to' => $this->faker->email,
            'reply_to' => $this->faker->email,
            'copy' => $this->faker->email,
            'subject' => $this->faker->text(),
            'text' => $this->faker->text(500),
            'html' => $this->faker->randomHtml(),
            'type' => Type::findOne(['code' => 'MAIL_TYPE_0'])->uuid,
            'sites' => Site::find()->select('uuid')->column()
        ]);

        $result = $template->insert();

        // Test whether mail template was created.
        $this->assertTrue($result);

        // Test whether mail template has a valid workflow record.
        $this->assertTrue($template->getWorkflow()->one() instanceof Workflow);

        // Test whether mail template has a type.
        $this->assertEquals(1, (int)$template->getTypeRelation()->count());
        $this->assertEquals(Site::find()->count(), $template->getSitesRelation()->count());
    }
    
    /**
     * Testing updating field attributes.
     */
    public function testTemplateUpdate()
    {
        $template = Template::findOne(['code' => 'MAIL_TEMPLATE_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($template);

        $template->subject = $this->faker->text();
        $template->type = $template->type->uuid;
        $template->sites = [];

        $result = $template->save();

        $this->assertTrue($result);
        $this->assertEquals(0, (int)$template->getSitesRelation()->count());
    }
    
    /**
     * Testing copying of mail template.
     */
    public function testTemplateCopy()
    {
        $template = Template::findOne(['code' => 'MAIL_TEMPLATE_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($template);

        // Creating user field clone
        $clone = $template->duplicate();

        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($clone->getWorkflow()->one() instanceof Workflow);

        $this->assertEquals(1, (int)$template->getSitesRelation()->count());
        $this->assertEquals(1, (int)$template->getTypeRelation()->count());
    }
    
    /**
     * Testing mail template deletion with all related records.
     */
    public function testTemplateDelete()
    {
        $template = Template::findOne(['code' => 'MAIL_TEMPLATE_0']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($template);

        // Delete user field
        $result = $template->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($template->refresh());
        $this->assertNull($template->getWorkflow()->one());

        $this->assertEquals(0, (int)$template->getSitesRelation()->count());
        $this->assertEquals(0, (int)$template->getTypeRelation()->count());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Template $template
     */
    protected function makeSure($template)
    {
        $this->assertTrue($template instanceof Template);
        $this->assertTrue($template->workflow instanceof Workflow);
        $this->assertTrue($template->type instanceof Type);
        $this->assertCount(1, $template->sites);
    }
}