<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $fields
 * @var \catalogs\modules\admin\models\Type $type
 */

$this->title = sprintf('%s — %s',
    Yii::t('app', 'Control panel'),
    Yii::t('catalogs', \catalogs\modules\admin\Module::$title)
);

// Registering assets
\catalogs\modules\admin\assets\CatalogsAsset::register($this);

?>
<div class="section-title">
    <?= Yii::t('catalogs/catalogs', 'Catalogs of type'); ?> "<?= $type->title; ?>"
</div>
<div id="catalogs-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'catalogs-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<div class="modal modal_warning" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>