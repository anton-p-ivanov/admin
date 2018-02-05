<?php

use Ramsey\Uuid\Uuid;

$return = [];

for ($i = 0; $i < 10; $i++) {
    $return[] = [
        'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-value-' . $i)->toString(),
        'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-1')->toString(),
        'value' => 'test value ' . $i,
        'label' => 'test label ' . $i,
    ];
}

return $return;