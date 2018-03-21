<?php

namespace catalogs\modules\admin\modules\fields\tests;

use app\models\Workflow;
use catalogs\modules\admin\models\Catalog;
use catalogs\modules\admin\modules\fields\models\Field;
use catalogs\modules\admin\modules\fields\models\Group;
use catalogs\modules\admin\modules\fields\tests\fixtures\FieldFixture;
use catalogs\modules\admin\modules\fields\tests\fixtures\GroupFixture;
use Codeception\Test\Unit;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

/**
 * Class GroupsTest
 *
 * @package catalogs\modules\admin\modules\fields\tests
 */
class GroupsTest extends Unit
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
            'groups' => GroupFixture::class,
            'fields' => FieldFixture::class,
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
     * Validate test
     */
    public function testValidate()
    {
        $group = new Group();

        // Empty required fields
        $this->assertFalse($group->validate(['title']));

        $group->active = -1;
        $this->assertFalse($group->validate(['active']));

        $group->sort = -1;
        $this->assertFalse($group->validate(['sort']));
    }

    /**
     * Create test
     */
    public function testCreate()
    {
        $group = new Group([
            'title' => $this->faker->text(),
            'active' => 1,
            'sort' => 100,
            'catalog_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'catalog-0')->toString()
        ]);

        $result = $group->insert();

        // Test whether field was created.
        $this->assertTrue($result);
        $this->assertTrue($group instanceof Group);
        $this->assertTrue($group->catalog instanceof Catalog);
        $this->assertTrue($group->workflow instanceof Workflow);
    }

    /**
     * Update test
     */
    public function testUpdate()
    {
        $group = Group::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'group-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($group);

        $group->catalog_uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'catalog-1')->toString();
        $result = $group->update();

        $this->assertNotFalse($result);
        $this->assertTrue($group->catalog instanceof Catalog);
    }

    /**
     * Copy test
     */
    public function testCopy()
    {
        $group = Group::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'group-0')->toString());

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($group);

        /* @var Group $clone */
        $clone = $group->duplicate();
        $clone->catalog_uuid = $group->catalog_uuid;
        $clone->title = $this->faker->text();

        $result = $clone->save();

        $this->assertTrue($result);
        $this->assertTrue($clone instanceof Group);
        $this->assertTrue($clone->catalog instanceof Catalog);
        $this->assertTrue($clone->workflow instanceof Workflow);
    }

    /**
     * Delete test
     */
    public function testDelete()
    {
        $group = Group::findOne(Uuid::uuid3(Uuid::NAMESPACE_URL, 'group-0')->toString());
        $uuid = $group->getFields()->select('uuid')->column();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($group);

        $result = $group->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($group->refresh());

        $this->assertTrue(Field::find()->where(['uuid' => $uuid])->count() == count($uuid));
        $this->assertNull(Workflow::findOne($group->workflow_uuid));
    }

    /**
     * @param Group $group
     */
    protected function makeSure($group)
    {
        $this->assertTrue($group instanceof Group);
        $this->assertTrue($group->catalog instanceof Catalog);
        $this->assertTrue($group->workflow instanceof Workflow);
        $this->assertTrue((int) $group->getFields()->count() > 0);
    }
}