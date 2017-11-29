<?php

use mail\models\Template;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Template $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'subject',
        'format' => 'raw',
        'value' => function (Template $data) {
            return Html::a($data->subject, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('mail', 'View & edit template properties'),
                'data-toggle' => 'modal',
                'data-target' => '#templates-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'from',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'to',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'code',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'workflow.modified_date',
        'format' => 'datetime',
        'options' => ['width' => 250]
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