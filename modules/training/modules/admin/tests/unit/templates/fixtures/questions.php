<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

Yii::setAlias('@training', '@app/modules/training');

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'question-' . $index)->toString(),
    'title' => $faker->text(),
    'description' => $faker->text(),
    'active' => true,
    'type' => \training\models\Question::TYPE_DEFAULT,
    'sort' => 100,
    'value' => 10,
    'lesson_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'lesson-0')->toString(),
];