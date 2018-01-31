<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

Yii::setAlias('@fields', '@app/modules/fields');

use Ramsey\Uuid\Uuid;
use fields\models\Field;

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-' . $index)->toString(),
    'label' => $faker->text(),
    'description' => $faker->text(500),
    'code' => 'CATALOG_FIELD_' . $index,
    'type' => $index == 0 ? Field::FIELD_TYPE_LIST : Field::FIELD_TYPE_DEFAULT,
    'multiple' => $index == 0,
    'default' => '',
    'options' => '',
    'active' => 1,
    'list' => 0,
    'sort' => 100,
    'catalog_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'catalog-' . $index)->toString(),
    'group_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'group-' . $index)->toString(),
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-field-' . $index)->toString()
];