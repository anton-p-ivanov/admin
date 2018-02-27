<?php
/**
 * @var \accounts\models\AccountStatus $status
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('accounts/discounts', 'Back to statuses` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                'statuses/index', 'account_uuid' => $status->account_uuid
            ],
        ],
        [
            'label' => Yii::t('accounts/discounts', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#discounts-modal',
                'data-reload' => 'true',
                'data-pjax' => 'false',
                'data-persistent' => 'true'
            ],
            'url' => [
                'discounts/create', 'status_uuid' => $status->uuid
            ],
        ]
    ],
    'group-2' => [
        [
            'label' => '<i class="material-icons">more_vert</i>',
            'encode' => false,
            'menuOptions' => ['class' => 'dropdown dropdown_right'],
            'items' => [
                [
                    'label' => Yii::t('accounts/discounts', 'Refresh'),
                    'url' => \yii\helpers\Url::current(),
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-toggle' => 'pjax',
                        'data-target' => '#discounts-pjax',
                    ])
                ]
            ]
        ],
    ],
    'selected' => [
        [
            'label' => '<i class="material-icons">delete</i>',
            'encode' => false,
            'url' => ['discounts/delete'],
            'options' => [
                'data-http-method' => 'delete',
                'data-confirm' => 'true',
                'data-toggle' => 'action',
                'data-pjax' => 'false',
                'title' => Yii::t('accounts/discounts', 'Delete selected items')
            ],
        ],
    ],
];