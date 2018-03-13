<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \storage\models\Storage $storage
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('storage', \storage\Module::$title),
    Yii::t('storage/versions', 'Versions')
);

// Registering assets
storage\assets\VersionsAsset::register($this);
?>
<div class="section-title">
    <?= Yii::t('storage/versions', 'Versions for file'); ?> "<?= $storage->title; ?>"
</div>
<div id="versions-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
        'options' => ['class' => 'toolbar']
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'versions-grid',
        'dataProvider' => $dataProvider,
        'layout' => '{items}{pager}',
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
        'columns' => require_once ".grid.php"
    ]); ?>

</div>

<?= \yii\helpers\Html::fileInput('files[]', '', ['multiple' => true, 'data' => [
    'url' => Yii::$app->params['files_host'] . '/upload',
    'move-url' => Yii::$app->params['files_host'] . '/move',
    'hash-url' => Yii::$app->params['files_host'] . '/hash',
]]); ?>

<div class="modal" id="upload-modal" role="dialog" data-persistent="true">
    <?= $this->render('@storage/views/storage/upload'); ?>
</div>

<div class="modal modal_warning" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>