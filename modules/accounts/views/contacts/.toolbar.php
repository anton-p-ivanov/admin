<?php
/**
 * @var \accounts\models\Account $account
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('accounts/contacts', 'Back to accounts` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                'accounts/index',
            ],
        ],
        [
            'label' => Yii::t('accounts/contacts', 'Add'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#contacts-modal',
                'data-reload' => 'true',
                'data-pjax' => 'false',
                'data-persistent' => 'true'
            ],
            'url' => [
                'contacts/create', 'account_uuid' => $account->uuid
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
                    'label' => Yii::t('accounts/contacts', 'Refresh'),
                    'url' => \yii\helpers\Url::current(),
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-toggle' => 'pjax',
                        'data-target' => '#contacts-pjax',
                    ])
                ]
            ]
        ],
    ],
    'selected' => [
        [
            'label' => '<i class="material-icons">delete</i>',
            'encode' => false,
            'url' => ['contacts/delete'],
            'options' => [
                'data-http-method' => 'delete',
                'data-confirm' => 'true',
                'data-toggle' => 'action',
                'data-pjax' => 'false',
                'title' => Yii::t('accounts/contacts', 'Delete selected items')
            ],
        ],
    ],
];