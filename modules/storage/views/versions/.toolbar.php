<?php
/**
 * @var \storage\models\Storage $storage
 * @var \storage\models\StorageTree $parentNode
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('storage/versions', 'Back to storage'),
                'data-pjax' => 'false'
            ],
            'url' => ['storage/index', 'tree_uuid' => $parentNode ? $parentNode->tree_uuid : null],
        ],
        [
            'label' => Yii::t('storage/versions', 'Add version'),
            'options' => [
                'data-toggle' => 'upload',
                'data-target' => '#versions-pjax',
                'data-pjax' => 'false'
            ],
            'url' => [
                'upload',
                'storage_uuid' => $storage->uuid
            ],
        ],
    ],
    'group-2' => [
        [
            'label' => '<i class="material-icons">more_vert</i>',
            'encode' => false,
            'menuOptions' => ['class' => 'dropdown dropdown_right'],
            'items' => [
                [
                    'label' => Yii::t('storage/versions', 'Refresh'),
                    'url' => \yii\helpers\Url::current(),
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-toggle' => 'pjax',
                        'data-target' => '#versions-pjax',
                    ])
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
                'title' => Yii::t('storage/versions', 'Delete selected items')
            ],
        ],
    ],
];