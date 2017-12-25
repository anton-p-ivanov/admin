<?php
/**
 * @var \accounts\models\AccountSettings $settings
 */
use accounts\models\Account;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Account $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Account $data) use ($settings) {
            $description = null;
            $title = Html::a($data->title, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('accounts', 'View & edit account properties'),
                'class' => 'account__title',
                'data-toggle' => 'modal',
                'data-target' => '#accounts-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);

            if ($settings->showDescription) {
                $description = Html::tag('span', strip_tags($data->description), [
                    'class' => 'account__description'
                ]);
            }

            return $title . $description;
        }
    ],
    [
        'attribute' => 'email',
        'format' => 'email',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'active',
        'label' => Yii::t('accounts', 'Act.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (Account $form) {
            return $form->isActive() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'sort',
        'options' => ['width' => 100],
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