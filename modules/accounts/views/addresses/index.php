<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \accounts\models\Account $account
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('accounts', \accounts\Module::$title),
    Yii::t('accounts/addresses', 'Addresses')
);

// Registering assets
\accounts\assets\AddressesAsset::register($this);
?>
<div class="account-title">
    <?= Yii::t('accounts/addresses', 'Addresses for account'); ?> "<?= $account->title; ?>"
</div>
<div id="addresses-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'addresses-grid',
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
        'columns' => require_once ".grid.php",
    ]); ?>

</div>

<div class="modal" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>