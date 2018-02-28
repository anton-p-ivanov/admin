<?php

use app\widgets\grid\ActionColumn;
use app\widgets\grid\CheckboxColumn;
use sales\modules\discounts\models\StatusDiscount;
use yii\helpers\Html;

return [
    [
        'class' => CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (StatusDiscount $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'discount_uuid',
        'format' => 'raw',
        'value' => function (StatusDiscount $model, $key) {
            return Html::a($model->discount->title, ['edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#discounts-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'options' => ['width' => 120],
        'headerOptions' => ['class' => 'text_right'],
        'contentOptions' => ['class' => 'text_right'],
        'attribute' => 'value',
        'format' => 'percent'
    ],
    [
        'options' => ['width' => 120],
        'headerOptions' => ['class' => 'text_center'],
        'contentOptions' => ['class' => 'text_center'],
        'attribute' => 'valid',
        'format' => 'boolean'
    ],
    [
        'options' => ['width' => 200],
        'attribute' => 'issue_date',
        'format' => 'datetime'
    ],
    [
        'options' => ['width' => 200],
        'attribute' => 'expire_date',
        'format' => 'datetime'
    ],
    [
        'class' => ActionColumn::class,
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ],
];