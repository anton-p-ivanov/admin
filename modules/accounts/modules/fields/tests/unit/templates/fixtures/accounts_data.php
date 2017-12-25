<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'account_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'account-' . $index)->toString(),
    'data' => '{"ACCOUNT_FIELD_TEST_01":"test value 01","ACCOUNT_FIELD_TEST_02":"test value 02"}',
];