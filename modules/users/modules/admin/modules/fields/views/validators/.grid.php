<?php

use users\modules\admin\modules\fields\models\FieldValidator;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (FieldValidator $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'type',
        'format' => 'raw',
        'value' => function (FieldValidator $model, $key) {
            return Html::a(FieldValidator::getTypes()[$model->type], ['edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#validators-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'active',
        'label' => Yii::t('fields', 'Act.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (FieldValidator $model) {
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