<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Form $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>
<?= $form->field($model, 'active')->switch(); ?>
<?= $form->field($model, 'active_dates')->rangeInput(['active_from_date', 'active_to_date']); ?>
<?= $form->field($model, 'title'); ?>

