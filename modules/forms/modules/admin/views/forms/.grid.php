<?php
/**
 * @var array $results
 * @var array $statuses
 * @var array $fields
 */

use forms\models\Form;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Form $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Form $data) {
            return Html::a($data->title, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('forms', 'View & edit form properties'),
                'data-toggle' => 'modal',
                'data-target' => '#forms-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'code',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'fields',
        'label' => Yii::t('forms', 'Fields'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Form $form) use ($fields) {
            $count = 0;

            if (array_key_exists($form->uuid, $fields)) {
                $count = $fields[$form->uuid];
            }

            return Html::a($count, ['fields/fields/index', 'form_uuid' => $form->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'statuses',
        'label' => Yii::t('forms', 'Statuses'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Form $form) use ($statuses) {
            $count = 0;

            if (array_key_exists($form->uuid, $statuses)) {
                $count = $statuses[$form->uuid];
            }

            return Html::a($count, ['statuses/index', 'form_uuid' => $form->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'results',
        'label' => Yii::t('forms', 'Res.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Form $form) use ($results) {
            $count = 0;

            if (array_key_exists($form->uuid, $results)) {
                $count = $results[$form->uuid];
            }

            return Html::a($count, ['/forms/results/index', 'form_uuid' => $form->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'active',
        'label' => Yii::t('forms', 'Use'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (Form $form) {
            return $form->isActive() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'sort',
        'label' => Yii::t('forms', 'Sort.'),
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 100],
    ],
    [
        'attribute' => 'workflow.modified_date',
        'format' => 'datetime',
        'options' => ['width' => 250]
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