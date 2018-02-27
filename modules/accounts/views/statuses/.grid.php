<?php

use accounts\models\AccountStatus;
use app\widgets\grid\ActionColumn;
use app\widgets\grid\CheckboxColumn;
use yii\helpers\Html;

return [
    [
        'class' => CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (AccountStatus $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'label' => Yii::t('accounts/statuses', 'Status'),
        'format' => 'raw',
        'value' => function (AccountStatus $model, $key) {
            return Html::a($model->status->title, ['statuses/edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#statuses-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    'valid:boolean',
    'issue_date:datetime',
    'expire_date:datetime',
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