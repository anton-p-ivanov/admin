<?php
/**
 * @var \catalogs\models\ElementTree $model
 */
return [
    [
        'label' => Yii::t('catalogs/elements', 'Open'),
        'options' => ['class' => $model->element->isSection() ? 'default' : null],
        'url' => ['index', 'tree_uuid' => $model->tree_uuid],
        'visible' => $model->element->isSection()
    ],
    [
        'options' => ['class' => 'dropdown__divider'],
        'visible' => $model->element->isSection(),
    ],
    [
        'label' => Yii::t('catalogs/elements', 'Edit'),
        'url' => ['edit', 'uuid' => $model->element_uuid, 'tree_uuid' => $model->tree_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => $model->element->isSection() ? '#section-modal' : '#element-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('catalogs/elements', 'Copy'),
        'url' => ['copy', 'uuid' => $model->element_uuid, 'tree_uuid' => $model->tree_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => $model->element->isSection() ? '#section-modal' : '#element-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true'
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('catalogs/elements', 'Delete'),
        'url' => ['delete', 'tree_uuid' => $model->tree_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-confirm' => 'true',
            'data-http-method' => 'delete',
            'data-pjax' => 'false',
            'data-toggle' => 'action'
        ]),
    ]
];
