<?php
/**
 * @var \catalogs\models\ElementTree $currentNode
 * @var \catalogs\models\ElementTree $parentNode
 */

return [
    'group-1' => [
        [
            'label' => '<i class="material-icons">arrow_back</i>',
            'encode' => false,
            'url' => ['index', 'tree_uuid' => $parentNode ? $parentNode->tree_uuid : null],
            'options' => [
                'title' => Yii::t('catalogs/locations', 'Up to previous level')
            ],
            'visible' => !$currentNode->isRoot()
        ],
        [
            'label' => $currentNode->isRoot() ? Yii::t('catalogs/locations', 'Catalog root') : $currentNode->element->title,
            'encode' => false,
            'options' => [
                'class' => 'toolbar__title',
                'title' => Yii::t('catalogs/locations', 'Current folder')
            ],
        ],
    ],
    'selected' => [
        [
            'label' => '<i class="material-icons">check</i>',
            'encode' => false,
            'url' => '#',
            'options' => [
                'data-toggle' => 'select',
                'data-pjax' => 'false',
                'title' => Yii::t('catalogs/locations', 'Confirm selection')
            ],
        ],
    ]
];