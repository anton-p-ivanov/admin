<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \partnership\models\Status $status
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('partnership', \partnership\Module::$title),
    Yii::t('partnership', 'Discounts')
);

?>
<div class="section-title">
    <?= Yii::t('partnership', 'Discounts for status'); ?> "<?= $status->title; ?>"
</div>

<?= $this->render('@sales/modules/discounts/views/discounts/index', [
    'dataProvider' => $dataProvider,
    'status' => $status
]); ?>