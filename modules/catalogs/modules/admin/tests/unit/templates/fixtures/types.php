<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'type-' . $index)->toString(),
    'sort' => 100,
    'code' => 'CATALOG_TYPE_' . $index,
];