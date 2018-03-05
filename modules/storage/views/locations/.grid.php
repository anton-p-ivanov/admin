<?php

use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'multiple' => false,
        'checkboxOptions' => function ($data) {
            $value = ['title' => $data['storage']['title'], 'uuid' => $data['tree_uuid']];
            return ['value' => \yii\helpers\Json::encode($value)];
        }
    ],
    [
        'attribute' => 'storage.title',
        'label' => 'Папка',
        'format' => 'raw',
        'value' => function ($data) {
            $title = Html::a($data['storage']['title'], ['index', 'tree_uuid' => $data['tree_uuid']]);
            return Html::tag('span', $title, [
                'class' => 'storage__title storage__title_' . strtolower($data['storage']['type'])
            ]);
        }
    ],
    [
        'attribute' => 'storage.workflow.created.fullname',
        'label' => 'Владелец',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'storage.workflow.modified_date',
        'label' => 'Изменено',
        'format' => 'datetime',
        'options' => ['width' => 200],
    ]
];