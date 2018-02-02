<?php

namespace accounts\modules\admin\tests;

use accounts\modules\admin\models\Type;
use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;

/**
 * Class TypesTest
 *
 * @package accounts\modules\admin\tests
 */
class TypesTest extends Unit
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
            'types' => 'accounts\modules\admin\tests\fixtures\TypeFixture',
            'types_i18n' => 'accounts\modules\admin\tests\fixtures\TypeI18NFixture'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@accounts', '@app/modules/accounts');

        $this->faker = Factory::create();
    }

    /**
     * Validate test.
     */
    public function testValidate()
    {
        $type = new Type();

        // empty title
        $type->title = null;
        $this->assertFalse($type->validate(['title']));

        // too long title
        $type->title = \Yii::$app->security->generateRandomString(300);
        $this->assertFalse($type->validate(['title']));

        // non-unique code
        $type->sort = -100;
        $this->assertFalse($type->validate(['sort']));

        // valid attributes
        $type->default = 100;
        $this->assertFalse($type->validate(['default']));
    }

    /**
     * Create test.
     */
    public function testCreate()
    {
        $type = new Type([
            'title' => $this->faker->text(50),
            'title_ru_ru' => $this->faker->text(50),
            'title_en_us' => $this->faker->text(50),
            'default' => 1
        ]);

        $result = $type->insert();

        // Test whether element was created.
        $this->assertTrue($result);

        $this->makeSure($type);

        // Test whether there is only one default element.
        $this->assertTrue((int)Type::find()->where(['default' => 1])->count() === 1);

        // Test whether current element is default
        $this->assertTrue((int)Type::find()->where(['default' => 1, 'uuid' => $type->uuid])->count() === 1);
    }

    /**
     * Update test.
     */
    public function testUpdate()
    {
        /* @var Type $type */
        $type = Type::find()->multilingual()->where(['uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'type-0')->toString()])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($type);

        $type->default = 1;
        $type->sort = 200;

        $result = $type->save();

        // Test whether element was updates.
        $this->assertTrue($result);

        // Test whether there is only one default element.
        $this->assertTrue((int)Type::find()->where(['default' => 1])->count() === 0);
    }

    /**
     * Copy test.
     */
    public function testCopy()
    {
        /* @var Type $type */
        $type = Type::find()->multilingual()->where(['uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'type-0')->toString()])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($type);

        // Creating user field clone
        $clone = $type->duplicate();
        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue((int)Type::find()->where(['default' => 1])->count() === 1);
    }

    /**
     * Delete test.
     */
    public function testDelete()
    {
        /* @var Type $type */
        $type = Type::find()->multilingual()->where(['uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'type-0')->toString()])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($type);

        // Delete user field
        $result = $type->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($type->refresh());
        $this->assertEquals(0, (int) $type->getRelation('translations')->count());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Type $type
     */
    protected function makeSure($type)
    {
        $this->assertTrue($type instanceof Type);
        $this->assertEquals(2, (int) $type->getRelation('translations')->count());
        $this->assertTrue((int)Type::find()->where(['default' => 1])->count() === 1);
    }
}