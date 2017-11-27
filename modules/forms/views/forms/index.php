<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \forms\models\FormSettings $settings
 * @var boolean $isFiltered
 * @var array $results
 */

$this->title = sprintf('%s — %s',
    Yii::t('app', 'Control panel'),
    Yii::t('forms', Yii::$app->controller->module->title)
);

// Registering assets
forms\assets\FormsAsset::register($this);

?>
<div id="forms-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
        'isFiltered' => $isFiltered
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'forms-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<div class="modal" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('confirm'); ?>
</div>