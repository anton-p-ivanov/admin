<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \catalogs\modules\admin\models\Catalog $catalog
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('catalogs', \catalogs\modules\admin\Module::$title),
    Yii::t('catalogs/fields', 'Groups')
);

// Registering assets
\catalogs\modules\admin\modules\fields\assets\GroupsAsset::register($this);

?>
<div class="section-title">
    <?= Yii::t('catalogs/fields', 'Fields` groups for catalog'); ?> "<?= $catalog->title; ?>"
</div>
<div id="groups-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'groups-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once Yii::getAlias("@fields/views/groups/.grid.php"),
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<div class="modal modal_warning" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>