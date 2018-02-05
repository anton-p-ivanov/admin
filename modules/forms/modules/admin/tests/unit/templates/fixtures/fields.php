<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use fields\models\Field;
use Ramsey\Uuid\Uuid;

Yii::setAlias('@fields', '@app/modules/fields');

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-' . $index)->toString(),
    'label' => $faker->text(50),
    'description' => $faker->text(500),
    'code' => 'FORM_FIELD_' . $index,
    'type' => $index === 0 ? Field::FIELD_TYPE_DEFAULT : Field::FIELD_TYPE_LIST,
    'multiple' => false,
    'default' => '',
    'options' => '',
    'active' => true,
    'list' => false,
    'sort' => 100,
    'form_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'form-0')->toString(),
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-field-' . $index)->toString(),
];