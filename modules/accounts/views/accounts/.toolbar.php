<?php

return [
    'group-1' => [
        [
            'label' => Yii::t('accounts', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#accounts-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true',
            ],
            'url' => [
                'create',
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
                    'label' => Yii::t('accounts', 'Refresh'),
                    'url' => \yii\helpers\Url::current()
                ]
            ]
        ],
    ],
    'selected' => [
         [
            'label' => '<i class="material-icons">delete</i>',
            'encode' => false,
            'url' => ['delete'],
            'options' => [
                'data-http-method' => 'delete',
                'data-confirm' => 'true',
                'data-toggle' => 'action',
                'data-pjax' => 'false',
                'title' => Yii::t('accounts', 'Delete selected items')
            ],
        ],
    ]
];