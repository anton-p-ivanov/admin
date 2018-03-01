<?php

use users\models\UserAccount;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (UserAccount $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'account.title',
        'format' => 'raw',
        'value' => function (UserAccount $model) {
            return Html::a($model->account->title, ['edit', 'uuid' => $model->uuid], [
                'data-toggle' => 'modal',
                'data-target' => '#accounts-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    'position:text',
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