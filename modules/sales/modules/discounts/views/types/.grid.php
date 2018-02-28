<?php

use app\widgets\grid\ActionColumn;
use app\widgets\grid\CheckboxColumn;
use sales\modules\discounts\models\Discount;
use yii\helpers\Html;

return [
    [
        'class' => CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (Discount $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Discount $model, $key) {
            return Html::a($model->title, ['edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#discounts-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'code',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'value',
        'format' => 'percent',
        'options' => ['width' => 120],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
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