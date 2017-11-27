<?php
/**
 * @var \forms\models\Form $model
 */
return [
    [
        'label' => Yii::t('forms', 'Results'),
        'url' => ['results/index', 'form_uuid' => $model->uuid],
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('forms', 'Edit'),
        'url' => ['edit', 'uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#forms-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('forms', 'Copy'),
        'url' => ['copy', 'uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#forms-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('forms', 'Copy (with relatives)'),
        'url' => ['copy', 'uuid' => $model->uuid, 'deepCopy' => true],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#forms-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true'
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('forms', 'Delete'),
        'url' => ['delete', 'uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-confirm' => 'true',
            'data-http-method' => 'delete',
            'data-pjax' => 'false',
            'data-toggle' => 'action'
        ]),
    ]
];
