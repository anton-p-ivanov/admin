<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

// Registering assets
\fields\assets\PropertiesAsset::register($this);
\storage\assets\LocationsAsset::register($this);
?>
<div id="properties-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'properties-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>