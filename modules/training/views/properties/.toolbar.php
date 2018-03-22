<?php
/**
 * @var \training\models\Attempt $attempt
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'url' => ['attempts/index', 'test_uuid' => $attempt->test_uuid],
            'options' => [
                'title' => Yii::t('training/attempts', 'Back to attempts` list'),
                'class' => 'toolbar-btn toolbar-btn_back',
                'data-pjax' => 'false'
            ],
        ],
        [
            'label' => Yii::t('training/attempts', 'Attempt answers'),
            'options' => [
                'class' => 'toolbar-btn toolbar-btn_title',
            ],
        ],
        [
            'label' => '<i class="material-icons">file_download</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('training/attempts', 'Export attempt into file'),
            ],
            'items' => [
                [
                    'label' => Yii::t('training/attempts', 'Comma-Separated (CSV)'),
                    'url' => ['export', 'attempt_uuid' => $attempt->uuid, 'format' => 'csv'],
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-pjax' => 'false',
                    ])
                ],
                [
                    'label' => Yii::t('training/attempts', 'Microsoft Excel (XLSX)'),
                    'url' => ['export', 'attempt_uuid' => $attempt->uuid, 'format' => 'xlsx'],
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-pjax' => 'false',
                    ])
                ]
            ]
        ]
    ],
];