<?php
/**
 * @var \catalogs\modules\admin\models\Type $type
 */

return [
    'group-1' => [
        [
            'label' => Yii::t('catalogs/catalogs', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#catalogs-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true',
            ],
            'url' => [
                'create',
                'type_uuid' => $type ? $type->uuid : null
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
                    'label' => Yii::t('catalogs/catalogs', 'Refresh'),
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
                'title' => Yii::t('catalogs/catalogs', 'Delete selected items')
            ],
        ],
    ],
];