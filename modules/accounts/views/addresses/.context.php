<?php
/**
 * @var \accounts\models\AccountAddress $model
 */
return [
    [
        'label' => Yii::t('accounts/addresses', 'Edit'),
        'url' => ['addresses/edit', 'uuid' => $model->address_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#addresses-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('accounts/addresses', 'Copy'),
        'url' => ['addresses/copy', 'uuid' => $model->address_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#addresses-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('accounts/addresses', 'Delete'),
        'url' => ['addresses/delete', 'uuid' => $model->address_uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-confirm' => 'true',
            'data-http-method' => 'delete',
            'data-pjax' => 'false',
            'data-toggle' => 'action'
        ]),
    ]
];
