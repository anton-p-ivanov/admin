<?php
/**
 * @var \forms\models\FormSettings $settings
 * @var array $results
 */

use forms\models\Form;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Form $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Form $data) use ($settings) {
            $description = null;
            $title = Html::a($data->title, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('forms', 'View & edit form properties'),
                'class' => 'form__title',
                'data-toggle' => 'modal',
                'data-target' => '#forms-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);

            if ($settings->showDescription) {
                $description = Html::tag('span', strip_tags($data->description), [
                    'class' => 'form__description'
                ]);
            }

            return $title . $description;
        }
    ],
    [
        'attribute' => 'code',
        'options' => ['width' => 200],
    ],
    [
        'attribute' => 'results',
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Form $form) use ($results) {
            if (array_key_exists($form->uuid, $results)) {
                return $results[$form->uuid];
            }

            return 0;
        }
    ],
    [
        'attribute' => 'active',
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'form__in-use'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'html',
        'value' => function (Form $form) {
            return $form->isActive() ? '<i class="material-icons">check</i>' : '';
        }
    ],
    [
        'attribute' => 'sort',
        'format' => 'integer',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 100],
    ],
    [
        'attribute' => 'workflow.modified_date',
        'format' => 'datetime',
        'options' => ['width' => 250]
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