<?php
/**
 * @var \forms\models\Form $form
 */

use \forms\models\Form;

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
            return \yii\helpers\Html::a($data->title, ['results/index', 'form_uuid' => $data->uuid], [
                'title' => Yii::t('forms', 'View & edit form results'),
                'data-pjax' => 'false',
            ]);
        }
    ],
    [
        'label' => Yii::t('forms', 'Results'),
        'format' => 'html',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 150],
        'value' => function (Form $model) use ($results) {
            $count = 0;

            if (array_key_exists($model->uuid, $results)) {
                $count = $results[$model->uuid];
            }

            return \yii\helpers\Html::a($count, ['results/index', 'form_uuid' => $model->uuid], [
                'title' => Yii::t('forms', 'View & edit form results'),
                'data-pjax' => 'false',
            ]);
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
        'class' => \app\widgets\grid\ActionColumn::class,
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ]
];