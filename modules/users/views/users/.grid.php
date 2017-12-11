<?php

use accounts\models\Account;
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
                'class' => 'user__title',
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
        'contentOptions' => ['class' => 'accounts'],
        'value' => function (User $model) {
            if ($model->accounts) {
                $message = Yii::t('users', '{n,plural,=1{# account} other{# accounts}}', [
                    'n' => count($model->accounts)
                ]);

                $link = Html::a($message, '#', [
                    'data-toggle' => 'dropdown',
                    'class' => 'dropdown-link'
                ]);

                $items = \yii\helpers\ArrayHelper::getColumn($model->accounts, function (Account $account) {
                    return '<span>' . $account->title . '</span>';
                });

                $list = Html::ul($items, ['class' => 'dropdown', 'encode' => false]);

                return $link . $list;
            }

            return null;
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