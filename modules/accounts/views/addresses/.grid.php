<?php

use accounts\models\AccountAddress;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (AccountAddress $model) {
            return ['value' => $model->address_uuid];
        }
    ],
    [
        'attribute' => 'address.type.title',
        'options' => ['width' => '30%'],
        'format' => 'raw',
        'value' => function (AccountAddress $model) {
            $title = $model->address->type->title;

            return Html::a($title, ['addresses/edit', 'uuid' => $model->address_uuid], [
                'data-toggle' => 'modal',
                'data-target' => '#addresses-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    'address',
    [
        'class' => \app\widgets\grid\ActionColumn::class,
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ],
];