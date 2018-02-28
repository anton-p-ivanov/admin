<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \accounts\models\AccountStatus $status
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('accounts', \accounts\Module::$title),
    Yii::t('sales/discounts', 'Discounts')
);

?>
<div class="status-title">
    <?= Yii::t('accounts', 'Discounts for account status'); ?> "<?= $status->status->title; ?>"
</div>

<?= $this->render('@sales/modules/discounts/views/discounts/index', [
    'dataProvider' => $dataProvider,
    'status' => $status
]); ?>