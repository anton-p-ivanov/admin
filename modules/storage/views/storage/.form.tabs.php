<?php
/**
 * @var \storage\models\Storage $model
 */

return [
    [
        'id' => 'properties',
        'title' => Yii::t('storage', 'Properties'),
        'active' => true
    ],
    [
        'id' => 'versions',
        'title' => Yii::t('storage', 'Versions'),
        'url' => ['versions/index', 'storage_uuid' => $model->uuid],
        'visible' => !$model->isDirectory(),
    ],
];
