<?php
/**
 * @var storage\models\StorageTree $model
 */
return [
    [
        'label' => Yii::t('storage', 'Open'),
        'options' => ['class' => $model->storage->isDirectory() ? 'default' : null],
        'url' => ['index', 'tree_uuid' => $model->tree_uuid],
        'visible' => $model->storage->isDirectory()
    ],
    [
        'label' => Yii::t('storage', 'Edit'),
        'url' => ['edit', 'uuid' => $model->storage_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => $model->storage->isDirectory() ? '#storage-dir-modal' : '#storage-file-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('storage', 'Download'),
        'url' => ['download', 'uuid' => $model['storage']['file']['uuid']],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
        'visible' => !$model->storage->isDirectory()
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('storage', 'Versions'),
        'url' => ['versions/index', 'storage_uuid' => $model->storage_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
        'visible' => !$model->storage->isDirectory()
    ],
    [
        'options' => ['class' => 'dropdown__divider'],
        'visible' => !$model->storage->isDirectory()
    ],
    [
        'label' => Yii::t('storage', 'Delete'),
        'url' => ['delete', 'uuid' => $model->tree_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-confirm' => 'true',
            'data-http-method' => 'delete',
            'data-pjax' => 'false',
            'data-toggle' => 'action'
        ]),
    ]
];
