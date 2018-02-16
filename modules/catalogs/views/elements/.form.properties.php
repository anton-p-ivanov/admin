<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\models\Element $model
 * @var \yii\widgets\ActiveForm $form
 */
?>

<?= $form->field($model, 'active')->switch(); ?>
<?= $form->field($model, 'active_dates')->rangeInput(['active_from_date', 'active_to_date']); ?>
<?= $form->field($model, 'title')->cleanButton(); ?>
<?= $form->field($model, 'code')->cleanButton(); ?>