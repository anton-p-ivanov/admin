<?php

use training\modules\admin\models\Answer;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (Answer $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'answer',
        'format' => 'raw',
        'value' => function (Answer $data) {
            return Html::a($data->answer, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('training/answers', 'View & edit answer properties'),
                'data-toggle' => 'modal',
                'data-target' => '#answers-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'valid',
        'label' => Yii::t('training/answers', 'Val.'),
        'format' => 'boolean',
        'options' => ['width' => 100],
    ],
    [
        'attribute' => 'sort',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 150],
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