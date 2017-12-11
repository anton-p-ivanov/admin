<?php
/**
 * @var string $user_uuid
 */

return [
    'group-1' => [
        [
            'label' => Yii::t('users', 'Assign site'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#access-sites-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'sites/create',
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
                    'url' => ['sites/index', 'user_uuid' => $user_uuid]
                ]
            ]
        ],
    ],
    'selected' => [
        [
            'label' => '<i class="material-icons">delete</i>',
            'encode' => false,
            'url' => ['sites/delete'],
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