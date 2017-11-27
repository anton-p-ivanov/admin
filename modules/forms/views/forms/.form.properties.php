<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Form $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>
<?= $form->field($model, 'active')->switch(['label' => Yii::t('forms', 'Enable for public use')]); ?>
<?= $form->field($model, 'active_dates')->rangeInput(['active_from_date', 'active_to_date']); ?>
<?= $form->field($model, 'title'); ?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'code'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'sort'); ?>
    </div>
</div>

<?= $form->field($model, 'description')->multilineInput(); ?>

<div class="form-group__required form-group__hint">
    * <?= Yii::t('forms', 'Required fields'); ?>
</div>