<?php

return [
    'group-1' => [
        [
            'label' => Yii::t('forms', 'Manage'),
            'encode' => false,
            'url' => ['/forms/admin/forms']
        ],
    ],
    'group-2' => [
        [
            'label' => '<i class="material-icons">more_vert</i>',
            'encode' => false,
            'menuOptions' => ['class' => 'dropdown dropdown_right'],
            'items' => [
                [
                    'label' => Yii::t('forms', 'Refresh'),
                    'url' => \yii\helpers\Url::current(),
                    'template' => \yii\helpers\Html::a('{label}', '{url}', [
                        'data-toggle' => 'pjax',
                        'data-target' => '#results-pjax',
                    ])
                ]
            ]
        ],
    ],
];