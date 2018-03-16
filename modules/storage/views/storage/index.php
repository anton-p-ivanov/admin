<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \storage\models\StorageSettings $settings
 * @var \storage\models\StorageTree $parentNode
 * @var \storage\models\StorageTree $currentNode
 * @var bool $isFiltered
 */

$this->title = 'Панель управления — Библиотека файлов';

// Registering assets
storage\assets\StorageAsset::register($this);
storage\assets\LocationsAsset::register($this);
?>
<div id="storage-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
        'isFiltered' => $isFiltered
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'storage-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<?= \yii\helpers\Html::fileInput('files[]', '', ['multiple' => true, 'data' => [
    'url' => Yii::$app->params['files_host'] . '/upload',
    'move-url' => Yii::$app->params['files_host'] . '/move',
    'hash-url' => Yii::$app->params['files_host'] . '/hash',
]]); ?>

<div class="modal" id="upload-modal" role="dialog" data-persistent="true">
    <?= $this->render('upload'); ?>
</div>

<div class="modal modal_warning" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>