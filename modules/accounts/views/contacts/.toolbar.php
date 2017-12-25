<?php
/**
 * @var \accounts\models\Account $account
 * @var string $updateUrl
 */

return [
    'group-1' => [
        [
            'label' => Yii::t('contacts', 'Add'),
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
                    'label' => Yii::t('contacts', 'Refresh'),
                    'url' => $updateUrl,
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
                'title' => Yii::t('contacts', 'Delete selected items')
            ],
        ],
    ],
];