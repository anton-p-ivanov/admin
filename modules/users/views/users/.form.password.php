<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\User $model
 * @var \users\models\UserPassword $password
 * @var \app\widgets\form\ActiveForm $form
 */
?>

<?= $form->field($password, 'password_new')->passwordInput(); ?>
<?= $form->field($password, 'password_new_repeat')->passwordInput(); ?>
<?= $form->field($password, 'expired_date'); ?>
