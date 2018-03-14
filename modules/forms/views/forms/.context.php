<?php
/**
 * @var \forms\models\Form $model
 */
return [
    [
        'label' => Yii::t('forms', 'Results'),
        'url' => ['results/index', 'form_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-pjax' => 'false',
        ]),
    ],
];
