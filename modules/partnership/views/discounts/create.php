<?php
/**
 * @var \yii\web\View $this
 * @var \partnership\models\StatusDiscount $model
 */
?>

<?= $this->render('@sales/modules/discounts/views/discounts/.form.php', [
    'model' => $model,
    'title' => 'New discount'
]); ?>