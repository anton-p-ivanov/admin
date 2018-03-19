<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \training\models\Test $test
 */

$this->title = sprintf('%s — %s',
    Yii::t('training', \training\Module::$title),
    Yii::t('training/attempts', 'Attempts')
);

// Registering assets
\training\assets\AttemptsAsset::register($this);

?>
<div class="section-title">
    <?= Yii::t('training/attempts', 'Attempts for test'); ?> "<?= $test->title; ?>"
</div>
<div id="attempts-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'attempts-grid',
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