<?php
/**
 * @var \storage\models\StorageTree $currentNode
 * @var \storage\models\StorageTree $parentNode
 */
$tree_uuid = Yii::$app->request->get('tree_uuid');

return [
    'group-1' => [
        [
            'label' => $tree_uuid ? '<i class="material-icons">arrow_back</i>' : '<i class="material-icons">apps</i>',
            'encode' => false,
            'url' => ['index', 'tree_uuid' => $parentNode ? $parentNode->tree_uuid : null],
            'options' => [
                'title' => Yii::t('storage', 'Up to previous level')
            ]
        ],
        [
            'label' => Yii::t('storage', 'Create folder'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#storage-dir-modal',
                'data-reload' => 'true'
            ],
            'url' => [
                'create',
                'parent_uuid' => $tree_uuid
            ],
        ],
        [
            'label' => Yii::t('storage', 'Upload files'),
            'options' => [
                'data-toggle' => 'upload',
                'data-target' => '#storage-pjax',
                'data-url' => \yii\helpers\Url::to(['index', 'tree_uuid' => $tree_uuid]),
                'data-pjax' => 'false'
            ],
            'url' => [
                'upload',
                'parent_uuid' => $tree_uuid
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
            'url' => ['filter', 'tree_uuid' => $tree_uuid, 'filter_uuid' => Yii::$app->request->get('filter_uuid')],
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
                    'label' => Yii::t('storage', 'Settings'),
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
                    'label' => Yii::t('storage', 'Refresh'),
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
                'title' => Yii::t('storage', 'Delete selected items')
            ],
        ],
    ],
    'filtered' => [
        [
            'label' => '<i class="material-icons">filter_list</i>',
            'encode' => false,
            'url' => ['filter', 'tree_uuid' => $tree_uuid, 'filter_uuid' => Yii::$app->request->get('filter_uuid')],
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#filter-modal',
                'data-persistent' => 'true',
                'data-reload' => 'true',
                'title' => Yii::t('storage', 'Data filter settings.')
            ],
        ],
        [
            'label' => '<i class="material-icons">close</i>',
            'encode' => false,
            'url' => ['index', 'tree_uuid' => $tree_uuid],
            'options' => [
                'title' => Yii::t('storage', 'Reset filter.')
            ],
        ],
    ]
];