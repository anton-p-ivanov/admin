<?php

use storage\models\StorageVersion;

return [
    'file.name',
    [
        'attribute' => 'active',
        'header' => \yii\helpers\Html::tag('i', 'check', [
            'class' => "material-icons",
            'title' => Yii::t('storage', 'Activity')
        ]),
        'options' => ['width' => 60],
        'format' => 'raw',
        'value' => function (StorageVersion $model) {
            return $model->isActive() ? '<i class="material-icons text_success version_active">check</i>' : '';
        }
    ],
    [
        'attribute' => 'file.size',
        'format' => 'shortSize',
        'options' => ['width' => 150],
    ],
    [
        'attribute' => 'file.type',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'workflow.created_date',
        'options' => ['width' => 200],
        'format' => 'datetime',
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