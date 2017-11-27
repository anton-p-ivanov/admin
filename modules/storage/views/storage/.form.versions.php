<?php
/**
 * @var \yii\web\View $this
 * @var storage\models\Storage $model
 */
?>

<?= $this->render('@storage/views/versions/index', [
    'storage_uuid' => $model->uuid,
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getVersions(),
        'pagination' => false,
        'sort' => false
    ])
]); ?>