<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'status-' . $index)->toString(),
    'title' => $faker->text(),
    'description' => $faker->text(),
    'active' => true,
    'default' => $index == 0,
    'sort' => 100,
    'form_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-0')->toString(),
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-status-' . $index)->toString(),
    'mail_template_uuid' => null
];