<?php


use i18n\modules\admin\models\Language;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (Language $model) {
            return ['value' => $model->code];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Language $data) {
            return Html::a($data->title, ['edit', 'code' => $data->code], [
                'title' => Yii::t('i18n', 'View & edit language properties'),
                'data-toggle' => 'modal',
                'data-target' => '#languages-modal',
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
        'attribute' => 'default',
        'label' => \Yii::t('i18n', 'Def.'),
        'format' => 'boolean',
        'options' => ['width' => 80],
    ],
    [
        'attribute' => 'sort',
        'label' => \Yii::t('i18n', 'Sort.'),
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 80],
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