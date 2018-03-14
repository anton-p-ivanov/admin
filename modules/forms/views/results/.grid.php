<?php
/**
 * @var \forms\models\Form $form
 */
use forms\models\Result;
use forms\modules\admin\modules\fields\models\Field;

$columns = [];
$fields = array_filter($form->fields, function (Field $field) { return $field->list === 1; });

foreach ($fields as $field) {
    $columns[] = [
        'format' => 'raw',
        'label' => $field->label,
        'value' => function (Result $model) use ($field) {
//        @todo display field data
//            $data = \yii\helpers\Json::decode($model->data);
//            return $data[$field->code];
            return null;
        }
    ];
}

return \yii\helpers\ArrayHelper::merge(
    [
        [
            'class' => \app\widgets\grid\CheckboxColumn::class,
            'options' => ['width' => 72],
            'checkboxOptions' => function (Result $model) {
                return ['value' => $model->uuid];
            }
        ]
    ],
    $columns,
    [
        'status.title',
        [
            'attribute' => 'workflow.modified_date',
            'format' => 'datetime',
            'options' => ['width' => 200]
        ],
        [
            'attribute' => 'workflow.created.fullname',
            'format' => 'text',
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
        ]
    ]
);