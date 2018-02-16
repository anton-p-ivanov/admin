<?php

use catalogs\models\ElementTree;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 68],
        'checkboxOptions' => function (ElementTree $model) {
            return ['value' => $model->tree_uuid];
        }
    ],
    [
        'attribute' => 'element.title',
        'format' => 'raw',
        'value' => function (ElementTree $data) {
            $title = Yii::$app->formatter->asText($data->element->title);

            if ($data->element->isSection()) {
                $title = Html::a($title, ['index', 'tree_uuid' => $data->tree_uuid], [
                    'title' => Yii::t('catalogs/elements', 'Show folder\'s content')
                ]);
            }
            else {
                $title = Html::a($title, ['edit', 'uuid' => $data->element_uuid, 'tree_uuid' => $data->tree_uuid], [
                    'title' => Yii::t('catalogs/elements', 'View & edit element properties'),
                    'data-toggle' => 'modal',
                    'data-target' => '#element-modal',
                    'data-pjax' => 'false',
                    'data-reload' => 'true'
                ]);
            }

            return Html::tag('span', $title, [
                'class' => 'elements__title elements__title_' . strtolower($data->element->type)
            ]);
        }
    ],
    [
        'label' => Yii::t('catalogs/elements', 'Act.'),
        'attribute' => 'element.active',
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'raw',
        'value' => function (ElementTree $data) {
            if (!$data->element->isSection()) {
                return Yii::$app->formatter->asBoolean($data->element->active);
            }

            return '';
        }
    ],
    [
        'label' => Yii::t('catalogs/elements', 'Sort.'),
        'attribute' => 'element.sort',
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (ElementTree $data) {
            if (!$data->element->isSection()) {
                return Yii::$app->formatter->asInteger($data->element->sort);
            }

            return '';
        }
    ],
    [
        'attribute' => 'element.workflow.status',
        'options' => ['width' => 200],
        'value' => function (ElementTree $data) {
            if ($data->element->isSection()) {
                return '';
            }

            return \app\models\WorkflowStatus::getList()[$data->element['workflow']['status']];
        }
    ],
    [
        'attribute' => 'element.workflow.modified_date',
        'options' => ['width' => 200],
        'value' => function (ElementTree $data) {
            return Yii::$app->formatter->asDatetime($data->element['workflow']['modified_date']);
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