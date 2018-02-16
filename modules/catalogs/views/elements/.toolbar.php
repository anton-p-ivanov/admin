<?php
/**
 * @var \catalogs\models\ElementTree $currentNode
 * @var \catalogs\models\ElementTree $parentNode
 * @var \catalogs\models\Catalog $catalog
 */

use catalogs\models\Element;

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'url' => ['index', 'tree_uuid' => $parentNode ? $parentNode->tree_uuid : null],
            'options' => [
                'title' => Yii::t('catalogs/elements', 'Up to previous level'),
                'class' => 'toolbar-btn toolbar-btn_back'
            ],
            'visible' => !$currentNode->isRoot()
        ],
        [
            'label' => Yii::t('catalogs/elements', 'New section'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#section-modal',
                'data-reload' => 'true'
            ],
            'url' => [
                'create',
                'tree_uuid' => $currentNode->tree_uuid,
                'type' => Element::ELEMENT_TYPE_SECTION,
            ],
        ],
        [
            'label' => Yii::t('catalogs/elements', 'New element'),
            'options' => [
                'data-toggle' => 'modal',
                'data-target' => '#element-modal',
                'data-reload' => 'true'
            ],
            'url' => [
                'create',
                'tree_uuid' => $currentNode->tree_uuid,
                'type' => Element::ELEMENT_TYPE_ELEMENT,
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
                    'label' => Yii::t('catalogs/elements', 'Refresh'),
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
                'title' => Yii::t('catalogs/elements', 'Delete selected items')
            ],
        ],
    ],
];