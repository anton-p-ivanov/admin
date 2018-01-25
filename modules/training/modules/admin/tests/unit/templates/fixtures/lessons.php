<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'lesson-' . $index)->toString(),
    'title' => $faker->text(),
    'description' => $faker->text(500),
    'active' => true,
    'sort' => 100,
    'code' => 'TRAINING_LESSON_' . $index,
    'course_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'course-0')->toString(),
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-lesson-' . $index)->toString(),
];