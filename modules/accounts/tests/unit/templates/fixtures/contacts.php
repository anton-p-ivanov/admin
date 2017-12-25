<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'contact-' . $index)->toString(),
    'account_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'account-' . $index)->toString(),
    'user_uuid' => null,
    'email' => $faker->email,
    'fullname' => $faker->name,
    'position' => $faker->jobTitle,
    'sort' => 100,
];