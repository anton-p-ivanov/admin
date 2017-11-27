<?php
/**
 * @var \yii\web\View $this
 * @var \forms\modules\fields\models\Field $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'default'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'sort'); ?>
    </div>
</div>

<?= $form->field($model, 'options', ['options' => ['class' => 'form-group form-group_text']])->textarea(); ?>