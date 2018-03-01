<?php
/**
 * @var \yii\web\View $this
 * @var \fields\models\Field $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'active')->switch(); ?>
    </div>
</div>

<?= $form->field($model, 'label'); ?>
<?= $form->field($model, 'description')->textarea(); ?>
