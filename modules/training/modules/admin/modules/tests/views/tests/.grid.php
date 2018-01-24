<?php
/**
 * @var array $questions
 * @var array $attempts
 */

use training\modules\admin\models\Test;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Test $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Test $data) {
            return Html::a($data->title, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('training/tests', 'View & edit test properties'),
                'data-toggle' => 'modal',
                'data-target' => '#tests-modal',
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
        'value' => function (Test $model) use ($questions) {
            $count = 0;

            if (array_key_exists($model->uuid, $questions)) {
                $count = $questions[$model->uuid];
            }

            return Html::a($count, ['questions/index', 'test_uuid' => $model->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'attempts',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Test $model) use ($attempts) {
            $count = 0;

            if (array_key_exists($model->uuid, $attempts)) {
                $count = $attempts[$model->uuid];
            }

            return Html::a($count, ['attempts/index', 'test_uuid' => $model->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'label' => Yii::t('training/tests', 'Act.'),
        'attribute' => 'active',
        'format' => 'boolean',
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