<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var string $storage_uuid
 */

$updateUrl = \yii\helpers\Url::to(['versions/index', 'storage_uuid' => $storage_uuid]);
?>

<div id="versions-pjax" data-pjax-container="true" data-pjax-url="<?= $updateUrl; ?>">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
        'options' => ['class' => 'toolbar toolbar_light']
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'versions-grid',
        'dataProvider' => $dataProvider,
        'layout' => '{items}{pager}',
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_dense',
            'grid-view__table_light',
            'grid-view__table_fixed'
        ])],
        'columns' => require_once ".grid.php"
    ]); ?>

</div>
