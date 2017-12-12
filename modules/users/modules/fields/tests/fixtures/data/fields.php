<?php

return [
    'field1' => [
        'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'label' => 'Field 1',
        'description' => '',
        'code' => 'USER_FIELD_TEST_01',
        'type' => \users\modules\fields\models\Field::FIELD_TYPE_LIST,
        'multiple' => false,
        'default' => '',
        'options' => '',
        'active' => true,
        'list' => false,
        'sort' => 100,
        'workflow_uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'workflow-1')->toString()
    ],
];