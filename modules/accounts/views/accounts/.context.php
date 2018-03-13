<?php
/**
 * @var \accounts\models\Account $model
 */
return [
    [
        'label' => Yii::t('accounts', 'Edit'),
        'url' => ['edit', 'uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#accounts-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('accounts', 'Copy'),
        'url' => ['copy', 'uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#accounts-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('accounts', 'Copy (with relatives)'),
        'url' => ['copy', 'uuid' => $model->uuid, 'deep' => true],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#accounts-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('accounts', 'Custom fields'),
        'url' => ['properties/index', 'account_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('accounts', 'Addresses'),
        'url' => ['addresses/index', 'account_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
    ],
    [
        'label' => Yii::t('accounts', 'Contacts'),
        'url' => ['contacts/index', 'account_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
    ],
    [
        'label' => Yii::t('accounts', 'Managers'),
        'url' => ['managers/index', 'account_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
    ],
    [
        'label' => Yii::t('accounts', 'Statuses'),
        'url' => ['statuses/index', 'account_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('accounts', 'Delete'),
        'url' => ['delete', 'uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-confirm' => 'true',
            'data-http-method' => 'delete',
            'data-pjax' => 'false',
            'data-toggle' => 'action'
        ]),
    ]
];
