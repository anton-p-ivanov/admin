<?php
/**
 * @var array $fields
 */

use catalogs\modules\admin\models\Catalog;
use yii\helpers\Html;

return [
    [
        'class' => \app\widgets\grid\CheckboxColumn::className(),
        'options' => ['width' => 72],
        'checkboxOptions' => function (Catalog $model) {
            return ['value' => $model->uuid];
        }
    ],
    [
        'attribute' => 'title',
        'format' => 'raw',
        'value' => function (Catalog $data) {
            return Html::a($data->title, ['edit', 'uuid' => $data->uuid], [
                'title' => Yii::t('catalogs/catalogs', 'View & edit catalog properties'),
                'data-toggle' => 'modal',
                'data-target' => '#catalogs-modal',
                'data-pjax' => 'false',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]);
        }
    ],
    'code',
    'type.title',
    [
        'attribute' => 'fields',
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_right'],
        'headerOptions' => ['class' => 'text_right'],
        'format' => 'raw',
        'value' => function (Catalog $model) use ($fields) {
            $count = 0;

            if (array_key_exists($model->uuid, $fields)) {
                $count = $fields[$model->uuid];
            }

            return Html::a($count, ['fields/fields/index', 'catalog_uuid' => $model->uuid], [
                'title' => Yii::t('catalogs/catalogs', 'View catalog`s fields'),
                'data-pjax' => 'false',
            ]);
        }
    ],
    [
        'attribute' => 'active',
        'label' => Yii::t('catalogs/catalogs', 'Act.'),
        'options' => ['width' => 100],
        'contentOptions' => ['class' => 'text_center'],
        'headerOptions' => ['class' => 'text_center'],
        'format' => 'boolean',
    ],
    [
        'attribute' => 'sort',
        'options' => ['width' => 100],
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