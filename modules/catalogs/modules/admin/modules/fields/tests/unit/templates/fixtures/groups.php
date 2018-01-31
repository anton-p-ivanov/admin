<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'group-' . $index)->toString(),
    'title' => $faker->text(),
    'active' => 1,
    'sort' => 100,
    'catalog_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'catalog-' . $index)->toString(),
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-group-' . $index)->toString()
];