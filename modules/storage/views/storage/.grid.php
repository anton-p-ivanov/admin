<?php
/**
 * @var \storage\models\StorageSettings $settings
 */

use storage\models\StorageTree;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (StorageTree $model) {
            return ['value' => $model->tree_uuid];
        }
    ],
    [
        'attribute' => 'storage.title',
        'format' => 'raw',
        'value' => function (StorageTree $data) use ($settings) {
            $title = $data->storage->title;
            $description = null;

            if ($data->storage->isDirectory()) {
                $title = Html::a($title, ['index', 'tree_uuid' => $data->tree_uuid], [
                    'title' => Yii::t('storage', 'Show folder\'s content')
                ]);
            }
            else {
                $title = $data->storage->file->name;
                $title = Html::a($title, ['edit', 'uuid' => $data->storage_uuid], [
                    'title' => Yii::t('storage', 'View & edit file properties'),
                    'data-toggle' => 'modal',
                    'data-target' => '#storage-file-modal',
                    'data-pjax' => 'false',
                    'data-reload' => 'true'
                ]);
            }

            if ($settings->showDescription) {
                $description = Html::tag('span', strip_tags($data->storage->description), [
                    'class' => 'storage__description'
                ]);
            }

            return Html::tag('span', $title . $description, [
                'class' => 'storage__title storage__title_' . strtolower($data->storage->type)
            ]);
        }
    ],
    [
        'attribute' => 'storage.file.size',
        'format' => 'raw',
        'options' => ['width' => 200],
        'value' => function ($data) {
            if ($data->storage->isDirectory()) {
                return '';
            }

            return Yii::$app->formatter->asShortSize($data['storage']['file']['size']);
        }
    ],
    [
        'attribute' => 'storage.workflow.created.fullname',
        'options' => ['width' => 200],
        'value' => function ($data) {
            return $data['storage']['workflow']['created']['fullname'];
        }
    ],
    [
        'attribute' => 'storage.workflow.modified_date',
        'options' => ['width' => 200],
        'value' => function ($data) {
            return Yii::$app->formatter->asDatetime($data['storage']['workflow']['modified_date']);
        }
    ],
    [
        'class' => \app\widgets\grid\ActionColumn::className(),
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ],
];