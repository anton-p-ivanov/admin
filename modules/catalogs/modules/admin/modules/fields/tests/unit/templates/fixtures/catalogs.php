<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'catalog-' . $index)->toString(),
    'code' => 'CATALOG_' . $index,
    'sort' => 100,
    'active' => 1,
    'trade' => 0,
    'index' => 0,
    'type_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'type-' . $index)->toString(),
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-catalog-' . $index)->toString()
];