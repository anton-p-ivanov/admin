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
        'class' => \app\widgets\grid\CheckboxColumn::class,
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
        'attribute' => 'active',
        'label' => Yii::t('forms', 'Use'),
        'options' => ['width' => 80],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (Form $form) {
            return $form->isActive() ? '<i class="material-icons text_success">check</i>' : '';
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
        'options' => ['width' => 200]
    ],
    [
        'class' => \app\widgets\grid\ActionColumn::class,
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ],
];