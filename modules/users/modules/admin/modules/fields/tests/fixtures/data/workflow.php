<?php

use Ramsey\Uuid\Uuid;

$count = 2;
$codes = [
    'workflow-user-',
    'workflow-field-',
];

$workflow = [];

foreach ($codes as $code) {
    for ($index = 0; $index < $count; $index++) {
        $workflow[] = [
            'uuid' => Uuid::uuid3(Uuid::NAMESPACE_URL, $code . $index)->toString(),
            'status' => \app\models\WorkflowStatus::WORKFLOW_STATUS_DEFAULT
        ];
    }
}

return $workflow;
