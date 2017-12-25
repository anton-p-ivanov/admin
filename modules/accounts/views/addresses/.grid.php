<?php

use accounts\models\AccountAddress;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (AccountAddress $model) {
            return ['value' => $model->address_uuid];
        }
    ],
    [
        'attribute' => 'address.type.title',
        'options' => ['width' => '30%'],
    ],
    'address',
    [
        'class' => \app\widgets\grid\ActionColumn::className(),
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ],
];