<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

Yii::setAlias('@fields', '@app/modules/fields');

use Ramsey\Uuid\Uuid;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'validator-' . $index)->toString(),
    'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-' . $index)->toString(),
    'type' => \fields\models\FieldValidator::TYPE_STRING,
    'options' => '',
    'active' => 1,
    'sort' => 100,
];