<?php

use storage\models\StorageVersion;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (StorageVersion $model) {
            return ['value' => $model->file_uuid];
        }
    ],
    [
        'attribute' => 'file.name',
        'label' => Yii::t('storage/versions', 'File'),
        'format' => 'raw',
        'value' => function (StorageVersion $model) {
            return Html::a($model->file->name, ['edit', 'uuid' => $model->file_uuid], [
                'title' => Yii::t('storage', 'Rename file'),
                'data-toggle' => 'modal',
                'data-target' => '#versions-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'file.size',
        'format' => 'shortSize',
        'options' => ['width' => 150],
    ],
    [
        'attribute' => 'active',
        'label' => Yii::t('storage/versions', 'Act.'),
        'options' => ['width' => 80],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (StorageVersion $model) {
            return $model->isActive() ? '<i class="material-icons text_success">check</i>' : '';
        }
    ],
    [
        'attribute' => 'workflow.created_date',
        'options' => ['width' => 200],
        'format' => 'datetime',
    ],
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