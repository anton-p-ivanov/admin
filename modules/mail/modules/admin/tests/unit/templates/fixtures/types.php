<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$title = $faker->text(50);

return [
    'uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'mail-type-' . $index)->toString(),
    'title' => $faker->text(),
    'description' => $faker->text(),
    'code' => 'MAIL_TYPE_' . $index,
    'workflow_uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'workflow-' . $index)->toString()
];