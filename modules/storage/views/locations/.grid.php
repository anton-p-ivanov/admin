<?php
/**
 * @var bool $withFiles
 */

use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'multiple' => false,
        'checkboxOptions' => function ($data) use ($withFiles) {
            return [
                'value' => \yii\helpers\Json::encode(['title' => $data['storage']['title'], 'uuid' => $data['tree_uuid']]),
                'disabled' => $withFiles && ($data['storage']['type'] === \storage\models\Storage::STORAGE_TYPE_DIR)
            ];
        }
    ],
    [
        'attribute' => 'storage.title',
        'label' => Yii::t('storage', 'Folder / file'),
        'format' => 'raw',
        'value' => function ($data) use ($withFiles) {
            $title = $data['storage']['title'];
            if ($data['storage']['type'] === \storage\models\Storage::STORAGE_TYPE_DIR) {
                $title = Html::a($title, ['index', 'tree_uuid' => $data['tree_uuid'], 'withFiles' => $withFiles]);
            }

            return Html::tag('span', $title, [
                'class' => 'storage__title storage__title_' . strtolower($data['storage']['type'])
            ]);
        }
    ],
    [
        'attribute' => 'storage.workflow.created.fullname',
        'label' => Yii::t('storage', 'Owner'),
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'storage.workflow.modified_date',
        'label' => Yii::t('storage', 'Modified'),
        'format' => 'datetime',
        'options' => ['width' => 200],
    ]
];