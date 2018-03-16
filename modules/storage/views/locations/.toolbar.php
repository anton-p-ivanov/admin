<?php
/**
 * @var \storage\models\StorageTree $currentNode
 * @var \storage\models\StorageTree $parentNode
 * @var bool $withFiles
 */
$tree_uuid = Yii::$app->request->get('tree_uuid');

return [
    'group-1' => [
        [
            'label' => $tree_uuid ? '<i class="material-icons">arrow_back</i>' : '<i class="material-icons">apps</i>',
            'encode' => false,
            'url' => ['index', 'tree_uuid' => $parentNode ? $parentNode->tree_uuid : null, 'withFiles' => $withFiles],
            'options' => [
                'title' => Yii::t('storage', 'Up to previous level')
            ],
        ],
        [
            'label' => $tree_uuid ? $currentNode->storage->title : 'Media library',
            'encode' => false,
            'options' => [
                'class' => 'toolbar__title',
                'title' => Yii::t('storage', 'Current folder')
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
                'title' => Yii::t('storage', 'Confirm selection')
            ],
        ],
    ]
];