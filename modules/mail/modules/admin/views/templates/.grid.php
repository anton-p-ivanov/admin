<?php

use mail\models\Template;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
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
                'title' => Yii::t('mail/templates', 'View & edit template properties'),
                'class' => 'template__title',
                'data-toggle' => 'modal',
                'data-target' => '#templates-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'to',
        'options' => ['width' => 300],
    ],
    [
        'attribute' => 'workflow.modified_date',
        'format' => 'datetime',
        'options' => ['width' => 200]
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