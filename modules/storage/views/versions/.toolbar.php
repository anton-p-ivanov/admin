<?php
/**
 * @var string $storage_uuid
 * @var string $updateUrl
 */

return [
    'group-1' => [
        [
            'label' => Yii::t('storage', 'Add version'),
            'options' => [
                'data-toggle' => 'upload',
                'data-target' => '#versions-pjax',
                'data-url' => $updateUrl,
            ],
            'url' => [
                'versions/upload',
                'storage_uuid' => $storage_uuid
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
                    'label' => Yii::t('storage', 'Refresh'),
                    'url' => $updateUrl,
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-toggle' => 'pjax',
                        'data-target' => '#versions-pjax',
                    ])
                ]
            ]
        ],
    ]
];