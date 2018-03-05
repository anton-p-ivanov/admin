<?php
/**
 * @var storage\models\StorageVersion $model
 */
return [
    [
        'label' => Yii::t('storage/versions', 'Activate'),
        'options' => ['class' => 'default'],
        'url' => ['versions/activate', 'uuid' => $model->file_uuid],
        'visible' => !$model->isActive(),
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'action',
            'data-pjax' => 'false',
            'data-confirm' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('storage/versions', 'Rename'),
        'url' => ['versions/edit', 'uuid' => $model->file_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
            'data-toggle' => 'modal',
            'data-target' => '#versions-modal',
            'data-reload' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('storage/versions', 'Download'),
        'url' => ['storage/download', 'uuid' => $model->file_uuid, 'original' => true],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
    ],
    [
        'options' => ['class' => 'dropdown__divider'],
        'visible' => !$model->isActive()
    ],
    [
        'label' => Yii::t('storage/versions', 'Delete'),
        'url' => ['versions/delete', 'uuid' => $model->file_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
            'data-toggle' => 'action',
            'data-confirm' => 'true',
            'data-http-method' => 'delete'
        ]),
        'visible' => !$model->isActive()
    ]
];
