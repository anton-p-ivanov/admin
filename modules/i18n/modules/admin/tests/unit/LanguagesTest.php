<?php

namespace i18n\modules\admin\tests;

use Codeception\Test\Unit;
use Faker\Factory;
use i18n\modules\admin\models\Language;
use i18n\modules\admin\tests\fixtures\LanguageFixture;

/**
 * Class LanguagesTest
 * @package i18n\modules\admin\tests
 */
class LanguagesTest extends Unit
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
            'languages' => LanguageFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@i18n', '@app/modules/i18n');

        $this->faker = Factory::create();
    }

    /**
     * Testing some model validations.
     */
    public function testLanguageValidate()
    {
        $language = new Language();

        // Empty required fields
        $this->assertFalse($language->validate(['title']));
        $this->assertFalse($language->validate(['code']));

        // Non-unique code
        $language->code = 'en-US';
        $this->assertFalse($language->validate(['code']));

        // Invalid code
        $language->code = 'e2n_1S';
        $this->assertFalse($language->validate(['code']));
    }

    /**
     * Testing model creation.
     */
    public function testLanguageCreate()
    {
        $form = new Language([
            'title' => 'English (GB)',
            'default' => 1,
            'code' => 'en-GB'
        ]);

        $result = $form->insert();

        // Test whether field was created.
        $this->assertTrue($result);

        // One `default` item MUST BE in a set of languages
        $this->assertTrue((int)Language::find()->where(['default' => 1])->count() === 1);
    }

    /**
     * Testing updating model
     */
    public function testLanguageUpdate()
    {
        $language = Language::findOne(['code' => 'en-US']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($language);

        $language->code = 'en-GB';
        $result = $language->save();

        $this->assertTrue($result);
    }

    /**
     * Testing copying of model with all related data
     */
    public function testLanguageCopy()
    {
        $language = Language::findOne(['code' => 'en-US']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($language);

        // Creating form field clone
        $clone = $language->duplicate();
        $clone->code = 'en-GB';
        $clone->save();

        $language->refresh();

        $this->assertTrue($clone instanceof Language);
        $this->assertTrue($clone->isDefault());
        $this->assertFalse($language->isDefault());
    }

    /**
     * Languages deletion with all related records.
     */
    public function testLanguageDelete()
    {
        $language = Language::findOne(['code' => 'en-US']);

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($language);

        // Delete user field
        $result = $language->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($language->refresh());

        // One `default` item MUST BE in a set of languages
        $this->assertTrue((int)Language::find()->where(['default' => 1])->count() === 1);
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Language $language
     */
    protected function makeSure($language)
    {
        $this->assertTrue($language instanceof Language);
        $this->assertTrue($language->isDefault());
    }
}