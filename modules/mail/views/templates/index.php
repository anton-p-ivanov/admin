<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \mail\models\TemplateSettings $settings
 * @var boolean $isFiltered
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('mail', Yii::$app->controller->module->title),
    Yii::t('mail', 'Templates')
);

// Registering assets
\mail\assets\TemplatesAsset::register($this);

?>
<div id="templates-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
        'isFiltered' => $isFiltered
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'templates-grid',
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