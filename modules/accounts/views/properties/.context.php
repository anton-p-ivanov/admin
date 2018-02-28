<?php
/**
 * @var \accounts\models\Field $model
 * @var \accounts\models\Account $account
 */
return [
    [
        'label' => Yii::t('accounts/properties', 'Edit'),
        'url' => ['edit', 'account_uuid' => $account->uuid, 'field_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#property-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true'
        ]),
    ],
    [
        'label' => Yii::t('accounts/properties', 'Copy'),
        'url' => ['copy', 'account_uuid' => $account->uuid, 'field_uuid' => $model->uuid],
        'template' => \yii\helpers\Html::a('{label}', '{url}', [
            'data-toggle' => 'modal',
            'data-target' => '#property-modal',
            'data-pjax' => 'false',
            'data-reload' => 'true'
        ]),
    ],
];
