<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use Ramsey\Uuid\Uuid;

Yii::setAlias('@fields', '@app/modules/fields');
Yii::setAlias('@accounts', '@app/modules/accounts');

return [
    'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-' . $index)->toString(),
    'label' => 'Field ' . $index,
    'description' => '',
    'code' => 'ACCOUNT_FIELD_TEST_0' . $index,
    'type' => \fields\models\Field::FIELD_TYPE_LIST,
    'multiple' => false,
    'default' => '',
    'options' => '',
    'active' => true,
    'list' => false,
    'sort' => 100,
    'workflow_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'workflow-field-' . $index)->toString()
];