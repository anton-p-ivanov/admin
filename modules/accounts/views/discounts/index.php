<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \accounts\models\AccountStatus $status
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('accounts', \accounts\Module::$title),
    Yii::t('accounts/discounts', 'Discounts')
);

// Registering assets
\accounts\assets\DiscountsAsset::register($this);
?>
<div class="status-title">
    <?= Yii::t('accounts/discounts', 'Discounts for account status'); ?> "<?= $status->status->title; ?>"
</div>
<div id="discounts-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'discounts-grid',
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
        'columns' => require_once ".grid.php",
    ]); ?>

</div>

<div class="modal modal_warning" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>