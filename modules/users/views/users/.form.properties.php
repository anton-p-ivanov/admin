<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\User $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>

<div class="grid">
    <div class="grid__item"><?= $form->field($model, 'fname'); ?></div>
    <div class="grid__item"><?= $form->field($model, 'lname'); ?></div>
</div>
<?= $form->field($model, 'sname'); ?>
<?= $form->field($model, 'email'); ?>