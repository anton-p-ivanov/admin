<?php
/**
 * @var \training\modules\admin\models\Lesson $lesson
 * @var array $selected
 */

use training\models\Question;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Question $model) use ($selected) {
            return [
                'value' => $model->uuid,
                'checked' => in_array($model->uuid, $selected)
            ];
        }
    ],
    [
        'attribute' => 'title',
        'label' => $lesson->title,
    ],
    [
        'attribute' => 'type',
        'label' => Yii::t('training/tests', 'Type'),
        'options' => ['width' => 200],
        'value' => function (Question $model) {
            return Question::getTypes()[$model->type];
        }
    ],
    [
        'attribute' => 'sort',
        'label' => Yii::t('training/tests', 'Sort'),
        'options' => ['width' => 100]
    ],
    [
        'attribute' => 'value',
        'label' => Yii::t('training/tests', 'Value'),
        'options' => ['width' => 100]
    ],
];