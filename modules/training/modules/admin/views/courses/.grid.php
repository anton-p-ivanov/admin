<?php
/**
 * @var array $lessons
 * @var array $tests
 */

use training\modules\admin\models\Course;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Course $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Course $data) {
            return Html::a($data->title, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('training/courses', 'View & edit course properties'),
                'data-toggle' => 'modal',
                'data-target' => '#courses-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'lessons',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Course $model) use ($lessons) {
            $count = 0;

            if (array_key_exists($model->uuid, $lessons)) {
                $count = $lessons[$model->uuid];
            }

            return Html::a($count, ['lessons/index', 'course_uuid' => $model->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'tests',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Course $model) use ($tests) {
            $count = 0;

            if (array_key_exists($model->uuid, $tests)) {
                $count = $tests[$model->uuid];
            }

            return Html::a($count, ['tests/tests/index', 'course_uuid' => $model->uuid], [
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