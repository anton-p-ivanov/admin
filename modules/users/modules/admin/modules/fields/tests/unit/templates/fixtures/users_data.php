<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'user_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'user-' . $index)->toString(),
    'data' => '{"USER_FIELD_TEST_01":"test value 01","USER_FIELD_TEST_02":"test value 02"}',
];