<?php
/**
 * @var array $questions
 */

use training\modules\admin\models\Lesson;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (Lesson $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Lesson $data) {
            return Html::a($data->title, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('training/lessons', 'View & edit lesson properties'),
                'data-toggle' => 'modal',
                'data-target' => '#lessons-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'questions',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Lesson $model) use ($questions) {
            $count = 0;

            if (array_key_exists($model->uuid, $questions)) {
                $count = $questions[$model->uuid];
            }

            return Html::a($count, ['questions/index', 'lesson_uuid' => $model->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'code',
        'options' => ['width' => 200],
    ],
    [
        'label' => Yii::t('training/courses', 'Act.'),
        'attribute' => 'active',
        'format' => 'boolean',
        'options' => ['width' => 150],
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