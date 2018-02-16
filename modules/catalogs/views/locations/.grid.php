<?php

use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function ($data) {
            $value = ['title' => $data['element']['title'], 'uuid' => $data['tree_uuid']];
            return ['value' => \yii\helpers\Json::encode($value)];
        }
    ],
    [
        'attribute' => 'element.title',
        'label' => Yii::t('catalogs/locations', 'Section'),
        'format' => 'raw',
        'value' => function ($data) {
            $title = Html::a($data['element']['title'], ['index', 'tree_uuid' => $data['tree_uuid']]);
            return Html::tag('span', $title, [
                'class' => 'element__title element__title_' . strtolower($data['element']['type'])
            ]);
        }
    ],
    [
        'attribute' => 'element.workflow.created.fullname',
        'label' => Yii::t('catalogs/locations', 'Owner'),
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'element.workflow.modified_date',
        'label' => Yii::t('catalogs/locations', 'Modified'),
        'format' => 'datetime',
        'options' => ['width' => 200],
    ]
];