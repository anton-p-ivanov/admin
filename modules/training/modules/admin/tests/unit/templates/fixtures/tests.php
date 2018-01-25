<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'test-' . $index)->toString(),
    'course_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'course-0')->toString(),
    'active' => true,
    'title' => $faker->text(),
    'description' => $faker->text(500),
    'questions_random' => true,
    'answers_random' => true,
    'limit_attempts' => 10,
    'limit_time' => 30,
    'limit_percent' => 80,
    'limit_value' => 100,
    'limit_questions' => 0,
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-test-' . $index)->toString(),
];