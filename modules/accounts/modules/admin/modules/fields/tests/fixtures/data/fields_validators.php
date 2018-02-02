<?php

use Ramsey\Uuid\Uuid;
use fields\models\FieldValidator;

$return = [];
$index = 0;

foreach (FieldValidator::getTypes() as $type => $name) {
    $return[] = [
        'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-validator-' . $index)->toString(),
        'field_uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, 'field-1')->toString(),
        'type' => $type,
    ];
    $index++;
}

return $return;