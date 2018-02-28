<?php
/**
 * @var \accounts\models\Account $account
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'url' => ['accounts/index'],
            'options' => [
                'title' => Yii::t('accounts/properties', 'Back to accounts` list'),
                'class' => 'toolbar-btn toolbar-btn_back',
                'data-pjax' => 'false'
            ],
        ],
        [
            'label' => $account->title,
        ]
    ],
    'group-2' => [
        [
            'label' => '<i class="material-icons">more_vert</i>',
            'encode' => false,
            'menuOptions' => ['class' => 'dropdown dropdown_right'],
            'items' => [
                [
                    'label' => Yii::t('accounts/properties', 'Refresh'),
                    'url' => \yii\helpers\Url::current()
                ]
            ]
        ],
    ],
];