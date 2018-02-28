<?php
/**
 * @var \accounts\models\Account $account
 * @var \accounts\models\AccountProperty[] $properties
 * @var Field $model
 */

use accounts\models\Field;
use yii\helpers\Html;

return [
    [
        'label' => Yii::t('accounts/properties', 'Property'),
        'options' => ['width' => '20%'],
//        'contentOptions' => ['class' => 'property__title'],
        'attribute' => 'label',
    ],
    [
        'label' => Yii::t('accounts/properties', 'Value'),
        'format' => 'raw',
        'value' => function (Field $model) use ($properties) {
            if (array_key_exists($model->uuid, $properties)) {
                return $properties[$model->uuid]->value;
            }

            return null;
        }
    ],
    [
        'label' => '',
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column'],
        'format' => 'raw',
        'value' => function (Field $model) use ($account) {
            $url = ['edit', 'account_uuid' => $account->uuid, 'field_uuid' => $model->uuid];

            return Html::a('<i class="material-icons">edit</i>', $url, [
                'data-toggle' => 'modal',
                'data-target' => '#property-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
            ]);
        }
    ],
];