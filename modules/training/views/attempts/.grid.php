<?php
/**
 * @var \training\models\Test $test
 */

use training\models\Attempt;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (Attempt $model) {
            return ['value' => $model->uuid];
        }
    ],
    'user.fullname',
    [
        'attribute' => 'success',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_center text_success'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'raw',
        'value' => function (Attempt $data) {
            return $data->isSuccessful() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'begin_date',
        'format' => 'datetime',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'end_date',
        'format' => 'datetime',
        'options' => ['width' => 200],
    ],
    [
        'class' => \app\widgets\grid\ActionColumn::class,
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ]
];