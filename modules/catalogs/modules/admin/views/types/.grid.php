<?php
/**
 * @var array $catalogs
 */

use catalogs\modules\admin\models\Type;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
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
                'title' => Yii::t('catalogs/types', 'View & edit catalog`s type properties'),
                'data-toggle' => 'modal',
                'data-target' => '#types-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    [
        'attribute' => 'catalogs',
        'format' => 'raw',
        'options' => ['width' => 150],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'value' => function (Type $type) use ($catalogs) {
            $count = 0;
            if (array_key_exists($type->uuid, $catalogs)) {
                $count = $catalogs[$type->uuid];
            }

            return Html::a($count, ['catalogs/index', 'type_uuid' => $type->uuid], [
                'data-pjax' => 'false',
            ]);
        }
    ],
    [
        'attribute' => 'code',
        'options' => ['width' => 250],
    ],
    [
        'attribute' => 'sort',
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'options' => ['width' => 150],
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