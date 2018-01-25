<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'answer-' . $index)->toString(),
    'question_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'question-0')->toString(),
    'answer' => $faker->text(),
    'valid' => $index === 0,
    'sort' => 100,
];