<?php

namespace partnership\tests;

use Codeception\Test\Unit;
use Faker\Factory;
use partnership\models\Status;
use partnership\tests\fixtures\StatusFixture;
use partnership\tests\fixtures\StatusI18NFixture;
use yii\db\ActiveRecord;

/**
 * Class StatusesTest
 * @package partnership\tests
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
            'statuses' => StatusFixture::className(),
            'statuses_i18n' => StatusI18NFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@partnership', '@app/modules/partnership');

        $this->faker = Factory::create();
    }

    /**
     * Testing validations.
     */
    public function testStatusValidate()
    {
        $status = new Status();

        // Empty required fields
        $this->assertFalse($status->validate(['title']));

        // Non-unique code
        $status->code = 'status_0';
        $this->assertFalse($status->validate(['code']));
    }

    /**
     * Testing status create
     */
    public function testStatusCreate()
    {
        $status = new Status([
            'title' => $this->faker->text(),
            'title_ru_ru' => $this->faker->text(),
            'title_en_us' => $this->faker->text(),
            'code' => 'STATUS_CREATE_TEST'
        ]);

        $result = $status->insert();

        // Test whether field was created.
        $this->assertTrue($result);
        $this->makeSure($status);
    }

    /**
     * Testing element update
     */
    public function testStatusUpdate()
    {
        /* @var Status $status */
        $status = Status::find()->multilingual()->where(['code' => 'status_0'])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($status);

        $status->title = 'Updated title';
        $result = $status->save();

        $this->assertTrue($result);

        $this->makeSure($status);
    }

    /**
     * Testing element copy with all related data
     */
    public function testStatusCopy()
    {
        /* @var Status $status */
        $status = Status::find()->multilingual()->where(['code' => 'status_0'])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($status);

        // Creating form field clone
        $clone = $status->duplicate();
        $clone->code = $clone->code . '_clone';
        $result = $clone->save();

        $this->assertTrue($result);

        $this->makeSure($clone);
    }

    /**
     * Testing element deletion with all related records.
     */
    public function testStatusDelete()
    {
        $status = Status::find()->multilingual()->where(['code' => 'status_0'])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($status);

        // Delete user field
        $result = $status->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($status->refresh());
        $this->assertEquals(0, (int) $status->getRelation('translations')->count());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Status|ActiveRecord $status
     */
    protected function makeSure($status)
    {
        $this->assertTrue($status instanceof Status);
        $this->assertEquals(2, (int) $status->getRelation('translations')->count());
    }
}