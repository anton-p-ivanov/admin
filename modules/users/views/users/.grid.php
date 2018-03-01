<?php
/**
 * @var array $accounts
 * @var array $roles
 * @var array $sites
 */

use users\models\User;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::class,
        'options' => ['width' => 72],
        'checkboxOptions' => function (User $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'fullname',
        'format' => 'raw',
        'value' => function (User $model) {
            return Html::a($model->getFullName(), ['edit', 'uuid' => $model->uuid], [
                'title' => Yii::t('users', 'View & edit user properties'),
                'data-toggle' => 'modal',
                'data-target' => '#users-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'email',
        'format' => 'email',
    ],
    [
        'label' => Yii::t('users', 'Registered'),
        'attribute' => 'workflow.created_date',
        'format' => 'datetime',
        'options' => ['width' => 200]
    ],
    [
        'attribute' => 'workflow.modified_date',
        'format' => 'datetime',
        'options' => ['width' => 200]
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