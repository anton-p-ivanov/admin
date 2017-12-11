<?php

use users\modules\fields\models\Field;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Field $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'label',
        'format' => 'raw',
        'value' => function (Field $model, $key) {
            return \yii\helpers\Html::a($model->label, ['fields/edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#fields-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'multiple',
        'label' => Yii::t('fields', 'Mult.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'field_multiple'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (Field $model) {
            return $model->isMultiple() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'active',
        'label' => Yii::t('fields', 'Act.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'field_active'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (Field $model) {
            return $model->isActive() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'sort',
        'label' => Yii::t('fields', 'Sort.'),
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 80],
    ],
    [
        'attribute' => 'workflow.modified_date',
        'format' => 'datetime',
        'options' => ['width' => 200]
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