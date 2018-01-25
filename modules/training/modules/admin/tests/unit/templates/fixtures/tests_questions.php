<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

Yii::setAlias('@training', '@app/modules/training');

use Ramsey\Uuid\Uuid;

return [
    'test_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'test-' . $index)->toString(),
    'question_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'question-' . $index)->toString(),
];