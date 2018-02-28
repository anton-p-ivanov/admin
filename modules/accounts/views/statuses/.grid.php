<?php
/**
 * @var array $discounts
 */
use accounts\models\AccountStatus;
use app\widgets\grid\ActionColumn;
use app\widgets\grid\CheckboxColumn;
use yii\helpers\Html;

return [
    [
        'class' => CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (AccountStatus $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'label' => Yii::t('accounts/statuses', 'Status'),
        'format' => 'raw',
        'value' => function (AccountStatus $model, $key) {
            return Html::a($model->status->title, ['statuses/edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#statuses-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'label' => Yii::t('accounts/statuses', 'Discounts'),
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (AccountStatus $model) use ($discounts) {
            $count = 0;

            if (array_key_exists($model->uuid, $discounts)) {
                $count = $discounts[$model->uuid];
            }

            return Html::a($count, ['discounts/index', 'status_uuid' => $model->status_uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'valid',
        'format' => 'boolean',
        'options' => ['width' => 120],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
    ],
    [
        'attribute' => 'issue_date',
        'format' => 'datetime',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'expire_date',
        'format' => 'datetime',
        'options' => ['width' => 200],
    ],
    [
        'class' => ActionColumn::class,
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ],
];