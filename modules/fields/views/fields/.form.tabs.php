<?php
/**
 * @var \fields\models\Field $model
 */

return [
    [
        'id' => 'field-properties',
        'title' => Yii::t('fields', 'Properties'),
        'active' => true
    ],
    [
        'id' => 'field-values',
        'title' => Yii::t('fields', 'Values'),
        'url' => ['values/index', 'field_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true', 'data-values' => 'true']
    ],
    [
        'id' => 'field-validators',
        'title' => Yii::t('fields', 'Validators'),
        'url' => ['validators/index', 'field_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'field-extra',
        'title' => Yii::t('fields', 'Extra'),
    ],
];