<?php

use users\models\UserRole;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (UserRole $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'role.description',
        'format' => 'raw',
        'value' => function (UserRole $model) {
            return Html::a($model->role->description, ['roles/edit', 'uuid' => $model->uuid], [
                'data-toggle' => 'modal',
                'data-target' => '#roles-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'valid',
        'header' => \yii\helpers\Html::tag('i', 'check', [
            'class' => "material-icons",
            'title' => Yii::t('users', 'Valid')
        ]),
        'options' => ['width' => 72],
        'format' => 'raw',
        'value' => function (UserRole $model) {
            return $model->isValid() ? '<i class="material-icons text_success version_active">check</i>' : '';
        }
    ],
    'valid_from_date:datetime',
    'valid_to_date:datetime',
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