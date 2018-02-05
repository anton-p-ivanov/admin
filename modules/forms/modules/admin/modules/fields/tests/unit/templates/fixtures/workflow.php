<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'workflow-' . $index)->toString(),
];