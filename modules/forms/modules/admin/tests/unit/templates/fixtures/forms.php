<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-' . $index)->toString(),
    'code' => 'FORM_' . $index,
    'title' => $faker->text(),
    'description' => $faker->text(),
    'template' => '',
    'template_active' => false,
    'active' => true,
    'active_from_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
    'active_to_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
    'sort' => 100,
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-form-' . $index)->toString()
];