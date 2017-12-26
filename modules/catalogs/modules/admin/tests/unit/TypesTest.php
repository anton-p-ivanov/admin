<?php

namespace catalogs\modules\admin\tests;

use app\models\Workflow;
use catalogs\models\Type;
use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;

/**
 * Class TypesTest
 * @package catalogs\modules\admin\tests
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
            'workflow' => 'catalogs\modules\admin\tests\fixtures\WorkflowFixture',
            'types' => 'catalogs\modules\admin\tests\fixtures\TypeFixture',
            'types_i18n' => 'catalogs\modules\admin\tests\fixtures\TypeI18NFixture'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function _before()
    {
        \Yii::setAlias('@catalogs', '@app/modules/catalogs');

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
        $type->sort = -100;
        $this->assertFalse($type->validate(['sort']));

        // valid attributes
        $type->code = 'CATALOG_TYPE_0';
        $this->assertFalse($type->validate(['code']));
    }

    /**
     * Testing mail type creation.
     */
    public function testTypeCreate()
    {
        $type = new Type([
            'title' => $this->faker->text(50),
            'title_ru_ru' => $this->faker->text(50),
            'title_en_us' => $this->faker->text(50),
        ]);

        $result = $type->insert();

        // Test whether element was created.
        $this->assertTrue($result);

        $this->makeSure($type);

        // Test whether code was generated.
        $this->assertFalse($type->code === null);
    }

    /**
     * Testing updating field attributes.
     */
    public function testTypeUpdate()
    {
        /* @var Type $type */
        $type = Type::find()->multilingual()->where(['code' => 'CATALOG_TYPE_0'])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($type);

        $type->title = $this->faker->text(100);
        $type->sort = 200;

        $result = $type->save();

        // Test whether element was updates.
        $this->assertTrue($result);
    }

    /**
     * Testing copying of element.
     */
    public function testTypeCopy()
    {
        /* @var Type $type */
        $type = Type::find()->multilingual()->where(['code' => 'CATALOG_TYPE_0'])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($type);

        // Creating user field clone
        $clone = $type->duplicate();
        $clone->code = $clone->code . '_COPY';
        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($type->getWorkflow()->one() instanceof Workflow);
    }

    /**
     * Testing element deletion with all related records.
     */
    public function testTypeDelete()
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
        $this->assertEquals(0, Workflow::find()->where(['uuid' => $type->workflow_uuid])->count());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Type $type
     */
    protected function makeSure($type)
    {
        $this->assertTrue($type instanceof Type);
        $this->assertEquals(2, (int) $type->getRelation('translations')->count());
        $this->assertEquals(1, Workflow::find()->where(['uuid' => $type->workflow_uuid])->count());
    }
}