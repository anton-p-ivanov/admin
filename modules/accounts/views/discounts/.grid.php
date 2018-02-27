<?php

use accounts\models\AccountDiscount;
use app\widgets\grid\ActionColumn;
use app\widgets\grid\CheckboxColumn;
use yii\helpers\Html;

return [
    [
        'class' => CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (AccountDiscount $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'label' => Yii::t('accounts/discounts', 'Status'),
        'format' => 'raw',
        'value' => function (AccountDiscount $model, $key) {
            return Html::a($model->discount->title, ['discounts/edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#discounts-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'options' => ['width' => 100],
        'attribute' => 'value',
        'format' => 'percent'
    ],
    [
        'options' => ['width' => 100],
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