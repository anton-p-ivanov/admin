<?php
/**
 * @var \training\models\Test $test
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('training/attempts', 'Back to tests` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                'tests/index', 'course_uuid' => $test->course_uuid,
            ],
        ],
        [
            'label' => Yii::t('training/attempts', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#attempts-modal',
                'data-reload' => 'true',
                'data-pjax' => 'false',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create', 'test_uuid' => $test->uuid
            ],
        ],
        [
            'label' => '<i class="material-icons">file_download</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('training/attempts', 'Export attempts into file'),
            ],
            'items' => [
                [
                    'label' => Yii::t('training/attempts', 'Comma-Separated (CSV)'),
                    'url' => ['export', 'test_uuid' => $test->uuid, 'format' => 'csv'],
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-pjax' => 'false',
                    ])
                ],
                [
                    'label' => Yii::t('training/attempts', 'Microsoft Excel (XLSX)'),
                    'url' => ['export', 'test_uuid' => $test->uuid, 'format' => 'xlsx'],
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-pjax' => 'false',
                    ])
                ]
            ]
        ]
    ],
    'group-2' => [
        [
            'label' => '<i class="material-icons">more_vert</i>',
            'encode' => false,
            'menuOptions' => ['class' => 'dropdown dropdown_right'],
            'items' => [
                [
                    'label' => Yii::t('training/attempts', 'Refresh'),
                    'url' => \yii\helpers\Url::current(),
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-toggle' => 'pjax',
                        'data-target' => '#attempts-pjax',
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
                'title' => Yii::t('training/attempts', 'Delete selected items')
            ],
        ],
    ],
];