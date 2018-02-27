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
                'title' => Yii::t('accounts/statuses', 'Back to accounts` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                'accounts/index',
            ],
        ],
        [
            'label' => Yii::t('accounts/statuses', 'Add'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#statuses-modal',
                'data-reload' => 'true',
                'data-pjax' => 'false',
                'data-persistent' => 'true'
            ],
            'url' => [
                'statuses/create', 'account_uuid' => $account->uuid
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
                    'label' => Yii::t('accounts/statuses', 'Refresh'),
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
            'url' => ['statuses/delete'],
            'options' => [
                'data-http-method' => 'delete',
                'data-confirm' => 'true',
                'data-toggle' => 'action',
                'data-pjax' => 'false',
                'title' => Yii::t('accounts/statuses', 'Delete selected items')
            ],
        ],
    ],
];