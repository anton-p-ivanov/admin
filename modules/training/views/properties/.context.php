<?php
/**
 * @var \training\models\Question $model
 * @var \training\models\Attempt $attempt
 */
return [
    [
        'label' => Yii::t('training/attempts', 'Edit'),
        'url' => ['edit', 'attempt_uuid' => $attempt->uuid, 'question_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#property-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('training/attempts', 'Delete'),
        'url' => ['delete', 'attempt_uuid' => $attempt->uuid, 'question_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-confirm' => 'true',
            'data-http-method' => 'delete',
            'data-pjax' => 'false',
            'data-toggle' => 'action'
        ]),
    ]
];
