<?php

use accounts\models\AccountContact;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (AccountContact $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (AccountContact $model, $key) {
            return \yii\helpers\Html::a($model->fullname, ['contacts/edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#contacts-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'position',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'email',
        'format' => 'email',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'sort',
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 100],
    ],
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