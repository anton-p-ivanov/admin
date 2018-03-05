<?php
/**
 * @var \forms\modules\admin\models\FormStatus $defaultStatus
 */
use forms\models\FormStatus;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (FormStatus $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (FormStatus $model, $key) {
            return \yii\helpers\Html::a($model->title, ['statuses/edit', 'uuid' => $key], [
                'data-toggle' => 'modal',
                'data-target' => '#statuses-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'default',
        'label' => Yii::t('forms/statuses', 'Def.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => [
            'class' => !$defaultStatus ? 'text_center no-valid' : 'text_center',
            'title' => !$defaultStatus ? Yii::t('forms/statuses', 'There is no default status. Default status is required for web form.') : null
        ],
        'format' => 'html',
        'value' => function (FormStatus $status) {
            return $status->isDefault() ? '<i class="material-icons text_success">check</i>' : '';
        }
    ],
    [
        'attribute' => 'mail_template_uuid',
        'label' => Yii::t('forms/statuses', 'Temp.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (FormStatus $model) {
            return $model->hasTemplate() ? '<i class="material-icons text_success">check</i>' : '';
        }
    ],
    [
        'attribute' => 'sort',
        'label' => Yii::t('forms/statuses', 'Sort.'),
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 100],
    ],
    [
        'attribute' => 'workflow.modified_date',
        'format' => 'datetime',
        'options' => ['width' => 200]
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