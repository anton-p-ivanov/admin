<?php

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('fields', 'Back to elements` list'),
                'data-pjax' => 'false'
            ],
            'visible' => isset($returnUrl),
            'url' => [
                isset($returnUrl) ? $returnUrl : null,
            ],
        ],
        [
            'label' => Yii::t('fields', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#fields-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create',
            ],
        ],
        [
            'label' => Yii::t('fields', 'Groups'),
            'options' => [
                'data-pjax' => 'false',
            ],
            'url' => [
                'groups/index',
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
                    'label' => Yii::t('fields', 'Refresh'),
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
                'title' => Yii::t('fields', 'Delete selected items')
            ],
        ],
    ],
];