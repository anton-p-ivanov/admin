<?php

use fields\models\FieldValidator;

$types = FieldValidator::getTypes();

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (FieldValidator $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'type',
        'value' => function (FieldValidator $model) use ($types) {
            if (array_key_exists($model->type, $types)) {
                return $types[$model->type];
            }

            return null;
        }
    ],
    'options',
    [
        'attribute' => 'sort',
        'label' => Yii::t('fields/validators', 'Sort.'),
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 100],
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