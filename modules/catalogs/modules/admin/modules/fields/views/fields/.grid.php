<?php
/**
 * @var array $validators
 * @var array $values
 */

use catalogs\modules\admin\modules\fields\models\Field;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Field $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'label',
        'format' => 'raw',
        'value' => function (Field $model, $key) {
            return Html::a($model->label, ['edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#fields-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'validators',
        'format' => 'raw',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'value' => function (Field $model) use ($validators) {
            $count = 0;
            if (array_key_exists($model->uuid, $validators)) {
                $count = $validators[$model->uuid];
            }

            return Html::a($count, ['validators/index', 'field_uuid' => $model->uuid], [
                'data-pjax' => 'false',
            ]);
        }
    ],
    [
        'attribute' => 'values',
        'format' => 'raw',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'value' => function (Field $model) use ($values) {
            if (!$model->hasValues()) {
                return '&mdash;';
            }

            $count = 0;
            if (array_key_exists($model->uuid, $values)) {
                $count = $values[$model->uuid];
            }

            return Html::a($count, ['values/index', 'field_uuid' => $model->uuid], [
                'data-pjax' => 'false',
            ]);
        }
    ],
    [
        'attribute' => 'multiple',
        'label' => Yii::t('fields', 'Mult.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (Field $model) {
            return $model->isMultiple() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'active',
        'label' => Yii::t('fields', 'Act.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (Field $model) {
            return $model->isActive() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'sort',
        'label' => Yii::t('fields', 'Sort.'),
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 80],
    ],
    [
        'attribute' => 'workflow.modified_date',
        'format' => 'datetime',
        'options' => ['width' => 200]
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