<?php
/**
 * @var \catalogs\modules\admin\models\Catalog $catalog
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('catalogs/fields', 'Back to catalogs` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                '/catalogs/admin/catalogs/index',
            ],
        ],
        [
            'label' => Yii::t('fields', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#fields-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create',
                'catalog_uuid' => $catalog->uuid,
            ],
        ],
        [
            'label' => Yii::t('fields', 'Groups'),
            'options' => [
                'data-pjax' => 'false',
            ],
            'url' => [
                'groups/index',
                'catalog_uuid' => $catalog->uuid,
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
                    'label' => Yii::t('fields', 'Refresh'),
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
                'title' => Yii::t('fields', 'Delete selected items')
            ],
        ],
    ],
];