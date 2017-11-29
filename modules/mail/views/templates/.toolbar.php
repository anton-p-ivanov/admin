<?php

return [
    'group-1' => [
        [
            'label' => Yii::t('mail', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#templates-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create',
            ],
        ]
    ],
    'group-2' => [
        [
            'label' => '<i class="material-icons">search</i>',
            'encode' => false,
            'url' => ['search']
        ],
        [
            'label' => '<i class="material-icons">filter_list</i>',
            'encode' => false,
            'url' => ['filter', 'filter_uuid' => Yii::$app->request->get('filter_uuid')],
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#filter-modal',
                'data-persistent' => 'true',
                'data-reload' => 'true'
            ],
        ],
        [
            'label' => '<i class="material-icons">more_vert</i>',
            'encode' => false,
            'menuOptions' => ['class' => 'dropdown dropdown_right'],
            'items' => [
                [
                    'label' => Yii::t('mail', 'Settings'),
                    'url' => ['settings'],
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-toggle' => 'modal',
                        'data-target' => '#settings-modal'
                    ])
                ],
                [
                    'options' => ['class' => 'dropdown__divider']
                ],
                [
                    'label' => Yii::t('mail', 'Refresh'),
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
                'title' => Yii::t('mail', 'Delete selected items')
            ],
        ],
    ],
    'filtered' => [
        [
            'label' => '<i class="material-icons">filter_list</i>',
            'encode' => false,
            'url' => ['filter', 'filter_uuid' => Yii::$app->request->get('filter_uuid')],
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#filter-modal',
                'data-persistent' => 'true',
                'data-reload' => 'true',
                'title' => Yii::t('mail', 'Data filter settings.')
            ],
        ],
        [
            'label' => '<i class="material-icons">close</i>',
            'encode' => false,
            'url' => ['index'],
            'options' => [
                'title' => Yii::t('mail', 'Reset filter.')
            ],
        ],
    ]
];