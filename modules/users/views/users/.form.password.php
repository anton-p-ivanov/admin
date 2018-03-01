<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\User $model
 * @var \users\models\UserPassword $password
 * @var \app\widgets\form\ActiveForm $form
 */
?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($password, 'password_new')->passwordInput(); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($password, 'password_new_repeat')->passwordInput(); ?>
    </div>
</div>

<?= $form->field($password, 'expired_date'); ?>
