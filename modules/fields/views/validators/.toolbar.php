<?php
/**
 * @var \fields\models\Field $field
 */

return [
    'group-1' => [
        [
            'label' => Yii::t('fields/validators', 'Create'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#validators-modal',
                'data-reload' => 'true',
                'data-pjax' => 'false',
                'data-persistent' => 'true'
            ],
            'url' => [
                'validators/create', 'field_uuid' => $field->uuid
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
                    'label' => Yii::t('fields/validators', 'Refresh'),
                    'url' => \yii\helpers\Url::current()
                ]
            ]
        ],
    ],
    'selected' => [
        [
            'label' => '<i class="material-icons">delete</i>',
            'encode' => false,
            'url' => ['validators/delete'],
            'options' => [
                'data-http-method' => 'delete',
                'data-confirm' => 'true',
                'data-toggle' => 'action',
                'data-pjax' => 'false',
                'title' => Yii::t('fields/validators', 'Delete selected items')
            ],
        ],
    ],
];