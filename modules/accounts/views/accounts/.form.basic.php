<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\Account $model
 * @var \yii\widgets\ActiveForm $form
 */

?>

<?= $form->field($model, 'title'); ?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'web'); ?>
        <?= $form->field($model, 'email'); ?>

    </div>
    <div class="grid__item">
        <?= $form->field($model, 'phone'); ?>
        <?= $form->field($model, 'active')->switch(); ?>
    </div>
</div>



