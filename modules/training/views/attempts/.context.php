<?php
/**
 * @var \training\models\Attempt $model
 */
return [
    [
        'label' => Yii::t('training/attempts', 'Edit'),
        'url' => ['edit', 'uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#attempts-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('training/attempts', 'Copy'),
        'url' => ['copy', 'uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#attempts-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('training/attempts', 'Custom fields'),
        'url' => ['properties/index', 'result_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('training/attempts', 'Delete'),
        'url' => ['delete', 'uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-confirm' => 'true',
            'data-http-method' => 'delete',
            'data-pjax' => 'false',
            'data-toggle' => 'action'
        ]),
    ]
];
