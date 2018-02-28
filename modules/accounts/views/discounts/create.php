<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\AccountDiscount $model
 */
?>

<?= $this->render('@sales/modules/discounts/views/discounts/.form.php', [
    'model' => $model,
    'title' => 'New discount'
]); ?>