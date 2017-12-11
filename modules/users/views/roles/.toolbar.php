<?php
/**
 * @var string $user_uuid
 */

return [
    'group-1' => [
        [
            'label' => Yii::t('users', 'Assign role'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#access-roles-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'roles/create',
                'user_uuid' => $user_uuid
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
                    'label' => Yii::t('users', 'Refresh'),
                    'url' => ['roles/index', 'user_uuid' => $user_uuid]
                ]
            ]
        ],
    ],
    'selected' => [
        [
            'label' => '<i class="material-icons">delete</i>',
            'encode' => false,
            'url' => ['roles/delete'],
            'options' => [
                'data-http-method' => 'delete',
                'data-confirm' => 'true',
                'data-toggle' => 'action',
                'data-pjax' => 'false',
                'title' => Yii::t('users', 'Delete selected items')
            ],
        ],
    ]
];