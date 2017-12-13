<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'form-' . $index)->toString(),
    'code' => 'FORM_' . $index,
    'title' => $faker->text(),
    'description' => '',
    'template' => '',
    'template_active' => false,
    'active' => true,
    'active_from_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
    'active_to_date' => $faker->dateTime()->format('Y-m-d H:i:s'),
    'sort' => 100,
];