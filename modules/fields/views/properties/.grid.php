<?php
/**
 * @var \fields\models\Property[] $properties
 * @var array $editUrl
 */

use yii\helpers\Html;

return [
    [
        'label' => Yii::t('fields/properties', 'Property'),
        'options' => ['width' => '20%'],
        'attribute' => 'label',
        'format' => 'raw',
        'value' => function ($model) use ($editUrl) {
            $editUrl['field_uuid'] = $model->uuid;
            return Html::a($model->label, $editUrl, [
                'data-toggle' => 'modal',
                'data-target' => '#property-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'label' => Yii::t('fields/properties', 'Value'),
        'format' => 'raw',
        'value' => function ($model) use ($properties) {
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
        'value' => function ($model) use ($editUrl) {
            $editUrl['field_uuid'] = $model->uuid;
            return Html::a('<i class="material-icons">edit</i>', $editUrl, [
                'data-toggle' => 'modal',
                'data-target' => '#property-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
            ]);
        }
    ],
];