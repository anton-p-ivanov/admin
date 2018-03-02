<?php
/**
 * @var array $answers
 */

use training\modules\admin\models\Question;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (Question $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Question $data) {
            return Html::a($data->title, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('training/questions', 'View & edit question properties'),
                'data-toggle' => 'modal',
                'data-target' => '#questions-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'answers',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Question $model) use ($answers) {
            $count = 0;

            if (array_key_exists($model->uuid, $answers)) {
                $count = $answers[$model->uuid];
            }

            return Html::a($count, ['answers/index', 'question_uuid' => $model->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'type',
        'format' => 'text',
        'options' => ['width' => 200],
        'value' => function (Question $model) {
            return Question::getTypes()[$model->type];
        }
    ],
    [
        'label' => Yii::t('training/courses', 'Act.'),
        'attribute' => 'active',
        'format' => 'boolean',
        'options' => ['width' => 80],
    ],
    [
        'label' => Yii::t('training/courses', 'Sort.'),
        'attribute' => 'sort',
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
    ],
];