<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'value-' . $index)->toString(),
    'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-' . $index)->toString(),
    'value' => 'value_' . $index,
    'label' => 'label_' . $index,
    'sort' => 100,
];