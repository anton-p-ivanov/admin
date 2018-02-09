<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'user_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'user-' . $index)->toString(),
    'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-' . $index)->toString(),
    'value' => $faker->text(),
];