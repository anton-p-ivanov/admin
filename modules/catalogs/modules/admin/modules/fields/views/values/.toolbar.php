<?php
/**
 * @var \catalogs\modules\admin\modules\fields\models\Field $field
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'options' => [
                'title' => Yii::t('fields/values', 'Back to fields` list'),
                'data-pjax' => 'false'
            ],
            'url' => [
                '/catalogs/admin/fields/fields/index',
                'catalog_uuid' => $field->catalog_uuid
            ],
        ],
        [
            'label' => Yii::t('fields/values', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#values-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ],
            'url' => [
                'create',
                'field_uuid' => $field->uuid,
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
                    'label' => Yii::t('fields/values', 'Refresh'),
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
                'title' => Yii::t('fields/values', 'Delete selected items')
            ],
        ],
    ],
];