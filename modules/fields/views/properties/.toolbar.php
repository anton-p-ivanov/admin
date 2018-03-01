<?php
/**
 * @var string $returnUrl
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'url' => $returnUrl,
            'options' => [
                'title' => Yii::t('fields/properties', 'Back to elements` list'),
                'class' => 'toolbar-btn toolbar-btn_back',
                'data-pjax' => 'false'
            ],
        ],
        [
            'label' => $title,
        ]
    ],
    'group-2' => [
        [
            'label' => '<i class="material-icons">more_vert</i>',
            'encode' => false,
            'menuOptions' => ['class' => 'dropdown dropdown_right'],
            'items' => [
                [
                    'label' => Yii::t('users/properties', 'Refresh'),
                    'url' => \yii\helpers\Url::current()
                ]
            ]
        ],
    ],
];