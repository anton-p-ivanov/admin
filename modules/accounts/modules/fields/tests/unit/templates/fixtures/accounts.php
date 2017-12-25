<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'account-' . $index)->toString(),
    'title' => $faker->company,
    'description' => '',
    'details' => '',
    'email' => $faker->companyEmail,
    'web' => $faker->url,
    'phone' => $faker->phoneNumber,
    'active' => 1,
    'sort' => 100,
    'parent_uuid' => null,
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-' . $index)->toString(),
];