<?php

use app\widgets\grid\ActionColumn;
use app\widgets\grid\CheckboxColumn;
use fields\models\FieldValue;
use yii\helpers\Html;

return [
    [
        'class' => CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (FieldValue $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'label',
        'format' => 'raw',
        'value' => function (FieldValue $model, $key) {
            return Html::a($model->label, ['edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#values-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    'value',
    [
        'attribute' => 'sort',
        'label' => Yii::t('fields', 'Sort.'),
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 100],
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