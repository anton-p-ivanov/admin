<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'discount-' . $index)->toString(),
    'code' => 'discount_' . $index,
    'title' => $faker->text(200),
    'value' => $faker->randomFloat(4, 0.1, 0.5),
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-discount-' . $index)->toString(),
];