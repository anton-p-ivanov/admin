<?php
/**
 * @var array $properties
 * @var array $questions
 * @var \training\models\Attempt $attempt
 */

use training\models\Question;
use yii\helpers\Html;

return [
    [
        'label' => Yii::t('training/attempts', 'Question'),
        'attribute' => 'label',
        'format' => 'raw',
        'value' => function (Question $model) use ($attempt) {
            return Html::a($model->title, ['edit', 'attempt_uuid' => $attempt->uuid, 'question_uuid' => $model->uuid], [
                'data-toggle' => 'modal',
                'data-target' => '#property-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'label' => Yii::t('training/attempts', 'Answer(s)'),
        'format' => 'html',
        'value' => function (Question $model) use ($properties) {
            if (array_key_exists($model->uuid, $properties)) {
                return Html::ul(\yii\helpers\ArrayHelper::getColumn($properties[$model->uuid], 'answer'));
            }

            return null;
        }
    ],
    [
        'label' => Yii::t('training/attempts', 'Valid'),
        'options' => ['width' => '100'],
        'format' => 'html',
        'value' => function (Question $model) use ($questions) {
            if (array_key_exists($model->uuid, $questions)) {
                return Yii::$app->formatter->asBoolean($questions[$model->uuid]);
            }

            return '&mdash;';
        }
    ],
    [
        'label' => Yii::t('training/attempts', 'Score'),
        'options' => ['width' => '100'],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'integer',
        'value' => function (Question $model) use ($questions) {
            if (array_key_exists($model->uuid, $questions)) {
                return (int)$questions[$model->uuid] * $model->value;
            }

            return 0;
        },
    ],
    [
        'class' => \app\widgets\grid\ActionColumn::class,
        'items' => function (/** @noinspection PhpUnusedParameterInspection */ $model) use ($attempt) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ]
];