<?php

namespace users\modules\admin\tests;

use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;
use users\modules\admin\models\Role;
use users\modules\admin\tests\fixtures\RoleFixture;

/**
 * Class RolesTest
 *
 * @package users\modules\admin\tests
 */
class RolesTest extends Unit
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
            RoleFixture::class
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
        $role = new Role();

        // empty title
        $role->description = null;
        $this->assertFalse($role->validate(['description']));

        // too long title
        $role->description = $this->faker->text(500);
        $this->assertFalse($role->validate(['description']));

        // non-unique code
        $role->name = 'guest';
        $this->assertFalse($role->validate(['name']));

        // valid attributes
        $role->name = $this->faker->text(100);
        $this->assertFalse($role->validate(['name']));
    }

    /**
     * Create test.
     */
    public function testCreate()
    {
        $role = new Role([
            'name' => 'new-role',
            'description' => $this->faker->text(50),
            'description_ru_ru' => $this->faker->text(50),
            'description_en_us' => $this->faker->text(50),
        ]);

        $result = $role->insert();

        // Test whether element was created.
        $this->assertTrue($result);
        $this->assertNotNull($role->created_at);
        $this->assertNotNull($role->updated_at);

        $this->makeSure($role);
    }

    /**
     * Update test.
     */
    public function testUpdate()
    {
        /* @var Role $role */
        $role = Role::find()->multilingual()->where(['name' => 'guest'])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($role);

        $role->description = 'Guest user (updated)';

        $result = $role->save();

        // Test whether element was updates.
        $this->assertTrue($result);
    }

    /**
     * Copy test.
     */
    public function testCopy()
    {
        /* @var Role $role */
        $role = Role::find()->multilingual()->where(['name' => 'guest'])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($role);

        // Creating user field clone
        $clone = $role->duplicate();
        $clone->name = 'new-group';
        $clone->description = 'New group';

        $result = $clone->save();

        $this->assertTrue($result);
        $this->makeSure($clone);
    }

    /**
     * Delete test.
     */
    public function testDelete()
    {
        /* @var Role $role */
        $role = Role::find()->multilingual()->where(['name' => 'guest'])->one();

        // Make sure that the all fixtures is correctly loaded
        $this->makeSure($role);

        // Delete user field
        $result = $role->delete();

        // Make sure that user field and all related data was removed
        $this->assertTrue($result === 1);
        $this->assertFalse($role->refresh());
        $this->assertEquals(0, (int) $role->getRelation('translations')->count());
    }

    /**
     * Make sure that the all fixtures is correctly loaded
     * @param Role $role
     */
    protected function makeSure($role)
    {
        $this->assertTrue($role instanceof Role);
        $this->assertEquals(2, (int) $role->getRelation('translations')->count());
    }
}