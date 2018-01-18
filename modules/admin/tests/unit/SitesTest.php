<?php

namespace admin\tests;

use admin\models\Site;
use admin\tests\fixtures\SitesFixture;
use Codeception\Test\Unit;
use Faker\Factory;

/**
 * Class SitesTest
 * @package admin\tests
 */
class SitesTest extends Unit
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
            'sites' => SitesFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@admin', '@app/modules/admin');

        $this->faker = Factory::create();
    }

    /**
     * Testing some model validations.
     */
    public function testSiteValidate()
    {
        $site = new Site();

        // Empty required fields
        $this->assertFalse($site->validate(['title']));
        $this->assertFalse($site->validate(['code']));
        $this->assertFalse($site->validate(['url']));
        $this->assertFalse($site->validate(['email']));

        // Non-unique code
        $site->code = 'TIMBER_INDUSTRIES';
        $this->assertFalse($site->validate(['code']));

        // Invalid code
        $site->code = 'TIMBER INDUSTRIES';
        $this->assertFalse($site->validate(['code']));

        // Invalid email
        $site->email = 'invalid email';
        $this->assertFalse($site->validate(['email']));

        // Invalid URL
        $site->url = 'invalid url';
        $this->assertFalse($site->validate(['url']));

        // Long title
        $site->title = $this->faker->text(500);
        $this->assertFalse($site->validate(['title']));
    }

    /**
     * Testing model creation.
     */
    public function testSiteCreate()
    {
        $site = new Site([
            'title' => 'Surveillance & Co.',
            'active' => 1,
            'url' => 'https://www.surveillance-co.com',
            'email' => 'Surveillance & Co. <noreply@surveillance-co.com>',
            'sort' => 100,
            'code' => 'SURVEILLANCE_CO'
        ]);

        $result = $site->insert();

        // Test whether model was inserted.
        $this->assertTrue($result);
    }

    /**
     * Testing updating model
     */
    public function testSiteUpdate()
    {
        $site = Site::findOne(['code' => 'TIMBER_INDUSTRIES']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($site);

        $site->title = 'Timber Industries Update';
        $result = $site->save();

        $this->assertTrue($result);
    }

    /**
     * Testing copying of model with all related data
     */
    public function testSiteCopy()
    {
        $site = Site::findOne(['code' => 'TIMBER_INDUSTRIES']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($site);

        // Creating form field clone
        $clone = $site->duplicate();
        $clone->code = 'TIMBER_INDUSTRIES_COPY';
        $clone->save();

        $this->makeSure($clone);
    }

    /**
     * Model deletion with all related records.
     */
    public function testSiteDelete()
    {
        $site = Site::findOne(['code' => 'TIMBER_INDUSTRIES']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($site);

        // Delete model
        $result = $site->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($site->refresh());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Site $site
     */
    protected function makeSure($site)
    {
        $this->assertTrue($site instanceof Site);
    }
}