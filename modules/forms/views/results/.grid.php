<?php
/**
 * @var \forms\models\Form $form
 */
use forms\models\FormResult;
use forms\modules\admin\modules\fields\models\Field;

$columns = [];
$fields = array_filter($form->fields, function (Field $field) { return $field->list === 1; });

foreach ($fields as $field) {
    $columns[] = [
        'format' => 'raw',
        'label' => $field->label,
        'value' => function (FormResult $model) use ($field) {
            $data = \yii\helpers\Json::decode($model->data);
            return $data[$field->code];
        }
    ];
}

return \yii\helpers\ArrayHelper::merge(
    [
        [
            'class' => \app\widgets\grid\CheckboxColumn::className(),
            'options' => ['width' => 72],
            'checkboxOptions' => function (FormResult $model) {
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
            'class' => \app\widgets\grid\ActionColumn::className(),
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