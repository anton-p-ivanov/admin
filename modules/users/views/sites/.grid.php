<?php

use users\models\UserSite;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (UserSite $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'site_uuid',
        'format' => 'raw',
        'value' => function (UserSite $model) {
            return Html::a($model->site->title, ['sites/edit', 'uuid' => $model->uuid], [
                'data-toggle' => 'modal',
                'data-target' => '#sites-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'active',
        'header' => \yii\helpers\Html::tag('i', 'check', [
            'class' => "material-icons",
            'title' => Yii::t('users', 'Active')
        ]),
        'options' => ['width' => 72],
        'format' => 'raw',
        'value' => function (UserSite $model) {
            return $model->isActive() ? '<i class="material-icons text_success version_active">check</i>' : '';
        }
    ],
    'active_from_date:datetime',
    'active_to_date:datetime',
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