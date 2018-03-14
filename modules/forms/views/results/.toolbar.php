<?php
/**
 * @var \forms\models\Form $form
 * @var string $updateUrl
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('forms/results', 'Back to forms` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                'forms/index',
            ],
        ],
        [
            'label' => Yii::t('forms/results', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#results-modal',
                'data-reload' => 'true',
                'data-pjax' => 'false',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create', 'form_uuid' => $form->uuid
            ],
        ],
        [
            'label' => '<i class="material-icons">file_download</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('forms/results', 'Export results into file'),
            ],
            'items' => [
                [
                    'label' => Yii::t('forms/results', 'Comma-Separated (CSV)'),
                    'url' => ['export', 'form_uuid' => $form->uuid, 'format' => 'csv'],
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-pjax' => 'false',
                    ])
                ],
                [
                    'label' => Yii::t('forms/results', 'Microsoft Excel (XLSX)'),
                    'url' => ['export', 'form_uuid' => $form->uuid, 'format' => 'xlsx'],
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
                    'label' => Yii::t('forms/results', 'Refresh'),
                    'url' => \yii\helpers\Url::current(),
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-toggle' => 'pjax',
                        'data-target' => '#results-pjax',
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
                'title' => Yii::t('forms/results', 'Delete selected items')
            ],
        ],
    ],
];