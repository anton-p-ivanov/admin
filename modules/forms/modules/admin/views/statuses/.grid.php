<?php

use forms\models\FormStatus;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (FormStatus $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (FormStatus $model, $key) {
            return \yii\helpers\Html::a($model->title, ['statuses/edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#statuses-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'default',
        'label' => Yii::t('forms/statuses', 'Def.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'status__default'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (FormStatus $model) {
            return $model->isDefault() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'active',
        'label' => Yii::t('forms/statuses', 'Act.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'status__active'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (FormStatus $model) {
            return $model->isActive() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'sort',
        'label' => Yii::t('forms/statuses', 'Sort.'),
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 100],
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