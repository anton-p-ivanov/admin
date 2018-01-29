<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \catalogs\modules\admin\models\Catalog $catalog
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('catalogs', \catalogs\modules\admin\Module::$title),
    Yii::t('catalogs/groups', 'Groups')
);

// Registering assets
\catalogs\modules\admin\modules\fields\assets\GroupsAsset::register($this);

?>
<div class="catalog-title">
    <?= Yii::t('catalogs/groups', 'Fields` groups for catalog'); ?> "<?= $catalog->title; ?>"
</div>
<div id="groups-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'groups-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<div class="modal" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>