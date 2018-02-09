<?php

use users\modules\admin\models\Role;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Role $model) {
            return ['value' => $model->name];
        }
    ],
    [
        'attribute' => 'description',
        'format' => 'raw',
        'value' => function (Role $model) {
            return Html::a($model->description, ['edit', 'name' => $model->name], [
                'data-toggle' => 'modal',
                'data-target' => '#roles-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    'name',
    'created_at:datetime',
    'updated_at:datetime',
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