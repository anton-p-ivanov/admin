<?php
/**
 * @var \forms\models\Form $model
 */

return [
    [
        'id' => 'properties',
        'title' => Yii::t('forms', 'Properties'),
        'active' => true,
    ],
    [
        'id' => 'template',
        'title' => Yii::t('forms', 'Template'),
    ],
    [
        'id' => 'fields',
        'title' => Yii::t('forms', 'Fields'),
        'url' => ['fields/fields/index', 'form_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'statuses',
        'title' => Yii::t('forms', 'Statuses'),
        'url' => ['statuses/index', 'form_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'results',
        'title' => Yii::t('forms', 'Results'),
        'url' => ['results/index', 'form_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ]
];
