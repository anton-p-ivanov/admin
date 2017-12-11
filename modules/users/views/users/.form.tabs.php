<?php
/**
 * @var \users\models\User $model
 */

return [
    [
        'id' => 'tab-properties',
        'title' => Yii::t('users', 'Properties'),
        'active' => true,
    ],
    [
        'id' => 'tab-account',
        'title' => Yii::t('users', 'Accounts'),
        'url' => ['accounts/index', 'user_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'tab-fields',
        'title' => Yii::t('users', 'Fields'),
        'url' => ['data/index', 'user_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'tab-roles',
        'title' => Yii::t('users', 'Roles'),
        'url' => ['roles/index', 'user_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'tab-sites',
        'title' => Yii::t('users', 'Sites'),
        'url' => ['sites/index', 'user_uuid' => $model->uuid],
        'options' => ['data-remote' => 'true']
    ],
    [
        'id' => 'tab-extra',
        'title' => Yii::t('users', 'Extra'),
    ],
];
