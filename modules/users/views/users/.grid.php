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
        'class' => \app\widgets\grid\CheckboxColumn::className(),
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
        'attribute' => 'accounts',
        'format' => 'raw',
        'options' => ['width' => 150],
        'headerOptions' => ['class' => 'text_right'],
        'contentOptions' => ['class' => 'text_right'],
        'value' => function (User $model) use ($accounts) {
            $count = 0;

            if (array_key_exists($model->uuid, $accounts)) {
                $count = $accounts[$model->uuid];
            }

            return Html::a($count, ['accounts/index', 'user_uuid' => $model->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'roles',
        'label' => Yii::t('users', 'Roles'),
        'format' => 'raw',
        'options' => ['width' => 100],
        'headerOptions' => ['class' => 'text_right'],
        'contentOptions' => ['class' => 'text_right'],
        'value' => function (User $model) use ($roles) {
            $count = 0;

            if (array_key_exists($model->uuid, $roles)) {
                $count = $roles[$model->uuid];
            }

            return Html::a($count, ['roles/index', 'user_uuid' => $model->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'sites',
        'label' => Yii::t('users', 'Sites'),
        'format' => 'raw',
        'options' => ['width' => 100],
        'headerOptions' => ['class' => 'text_right'],
        'contentOptions' => ['class' => 'text_right'],
        'value' => function (User $model) use ($sites) {
            $count = 0;

            if (array_key_exists($model->uuid, $sites)) {
                $count = $sites[$model->uuid];
            }

            return Html::a($count, ['sites/index', 'user_uuid' => $model->uuid], [
                'data-pjax' => 'false'
            ]);
        }
    ],
    [
        'attribute' => 'workflow.modified_date',
        'format' => 'datetime',
        'options' => ['width' => 200]
    ],
    [
        'class' => \app\widgets\grid\ActionColumn::className(),
        'items' => function (
            /** @noinspection PhpUnusedParameterInspection */ $model
        ) {
            return require '.context.php';
        },
        'options' => ['width' => 72],
        'contentOptions' => ['class' => 'action-column action-column_right']
    ],
];