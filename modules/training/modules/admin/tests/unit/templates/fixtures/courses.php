<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'course-' . $index)->toString(),
    'title' => $faker->text(),
    'description' => $faker->text(500),
    'active' => true,
    'sort' => 100,
    'code' => 'TRAINING_COURSE_' . $index,
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-course-' . $index)->toString(),
];