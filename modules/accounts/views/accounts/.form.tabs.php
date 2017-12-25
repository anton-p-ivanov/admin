<?php
/**
 * @var \accounts\models\Account $model
 */

return [
    [
        'id' => 'properties',
        'title' => Yii::t('accounts', 'Properties'),
        'active' => true,
    ],
    [
        'id' => 'addresses',
        'title' => Yii::t('accounts', 'Addresses'),
        'visible' => !$model->isNewRecord,
        'url' => ['addresses/index', 'account_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'partnership',
        'title' => Yii::t('accounts', 'Partnership'),
        'visible' => !$model->isNewRecord,
    ],
    [
        'id' => 'fields',
        'title' => Yii::t('accounts', 'Fields'),
        'visible' => !$model->isNewRecord,
        'url' => ['data/index', 'account_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'contacts',
        'title' => Yii::t('accounts', 'Contacts'),
        'visible' => !$model->isNewRecord,
        'url' => ['contacts/index', 'account_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'extra',
        'title' => Yii::t('accounts', 'Extra'),
        'visible' => !$model->isNewRecord,
    ]
];
