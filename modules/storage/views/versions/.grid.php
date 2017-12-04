<?php

use storage\models\StorageVersion;

return [
    [
        'attribute' => 'file.name',
        'label' => Yii::t('storage', 'File'),
        'format' => 'html',
        'value' => function (StorageVersion $model) {
            return '<b>' . $model->file->name . '</b><span class="version__description">'
                . Yii::$app->formatter->asShortSize($model->file->size) . ' / '
                . $model->file->type . '</span>';
        }
    ],
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