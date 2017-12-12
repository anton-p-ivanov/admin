<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'template_uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'mail-template-' . $index)->toString(),
    'type_uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'mail-type-' . $index)->toString(),
];