<?php
/**
 * @var \forms\models\Form $form
 * @var array $properties
 * @var \fields\models\Field[] $fields
 */

use forms\models\Result;

$columns = [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (Result $model) {
            return ['value' => $model->uuid];
        }
    ]
];

foreach ($fields as $field) {
    $columns[] = [
        'format' => 'raw',
        'label' => $field->label,
        'value' => function (Result $model) use ($field, $properties) {
            if (isset($properties[$model->uuid][$field->uuid])) {
                return $properties[$model->uuid][$field->uuid];
            }

            return null;
        }
    ];
}

$columns[] = 'status.title';
$columns[] = [
    'attribute' => 'workflow.modified_date',
    'format' => 'datetime',
    'options' => ['width' => 200]
];
$columns[] = [
    'attribute' => 'workflow.created.fullname',
    'format' => 'text',
    'options' => ['width' => 200]
];
$columns[] = [
    'class' => \app\widgets\grid\ActionColumn::class,
    'items' => function (
        /** @noinspection PhpUnusedParameterInspection */ $model
    ) {
        return require '.context.php';
    },
    'options' => ['width' => 72],
    'contentOptions' => ['class' => 'action-column action-column_right']
];

return $columns;