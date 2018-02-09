<?php
/**
 * @var \users\modules\admin\models\Role $model
 */
return [
    [
        'label' => Yii::t('users/roles', 'Edit'),
        'url' => ['roles/edit', 'name' => $model->name],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#roles-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('users/roles', 'Copy'),
        'url' => ['roles/copy', 'name' => $model->name],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#roles-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('users/roles', 'Delete'),
        'url' => ['roles/delete', 'name' => $model->name],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-confirm' => 'true',
            'data-http-method' => 'delete',
            'data-pjax' => 'false',
            'data-toggle' => 'action'
        ]),
    ]
];
