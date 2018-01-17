<?php
/**
 * @var \i18n\modules\admin\models\Language $model
 */
return [
    [
        'label' => Yii::t('i18n', 'Edit'),
        'url' => ['edit', 'code' => $model->code],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#languages-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('i18n', 'Copy'),
        'url' => ['copy', 'code' => $model->code],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#languages-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true',
            'data-persistent' => 'true'
        ]),
    ],
    ['options' => ['class' => 'dropdown__divider']],
    [
        'label' => Yii::t('i18n', 'Delete'),
        'url' => ['delete', 'code' => $model->code],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-confirm' => 'true',
            'data-http-method' => 'delete',
            'data-pjax' => 'false',
            'data-toggle' => 'action'
        ]),
    ]
];
