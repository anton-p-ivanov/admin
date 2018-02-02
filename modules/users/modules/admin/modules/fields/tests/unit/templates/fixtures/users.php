<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'user-' . $index)->toString(),
    'email' => $faker->email,
    'fname' => $faker->firstName,
    'lname' => $faker->lastName,
    'sname' => '',
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-user-' . $index)->toString(),
];