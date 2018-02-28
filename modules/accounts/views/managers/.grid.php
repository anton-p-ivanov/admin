<?php

use accounts\models\AccountManager;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (AccountManager $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'manager_uuid',
        'options' => ['width' => '25%'],
        'format' => 'raw',
        'value' => function (AccountManager $model, $key) {
            return \yii\helpers\Html::a($model->manager->getFullName(), ['edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#managers-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    'comments',
    [
        'label' => Yii::t('accounts/managers', 'Sort.'),
        'attribute' => 'sort',
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 100],
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