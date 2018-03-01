<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \catalogs\models\ElementTree $parentNode
 * @var \catalogs\models\ElementTree $currentNode
 * @var \catalogs\models\Catalog $catalog
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('catalogs/elements', 'Catalogs'),
    $catalog->title,
    Yii::t('catalogs/elements', 'Elements')
);

// Registering assets
catalogs\assets\ElementsAsset::register($this);

?>
<div id="elements-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'elements-grid',
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