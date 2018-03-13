<?php
/**
 * @var array $accounts
 */

use accounts\models\Type;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (Type $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Type $data) {
            return Html::a($data->title, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('accounts/types', 'View & edit type properties'),
                'data-toggle' => 'modal',
                'data-target' => '#types-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'accounts',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'value' => function (Type $type) use ($accounts) {
            if (array_key_exists($type->uuid, $accounts)) {
                return $accounts[$type->uuid];
            }

            return 0;
        }
    ],
    [
        'attribute' => 'sort',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 150],
    ],
    [
        'attribute' => 'default',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 150],
        'format' => 'html',
        'value' => function (Type $type) {
            return $type->isDefault() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'class' => \app\widgets\grid\ActionColumn::class,
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ],
];