<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Course $model
 * @var \app\models\Workflow $workflow
 * @var \app\widgets\form\ActiveForm $form
 */
?>
<?= $form->field($model, 'active')->switch(); ?>
<?= $form->field($model, 'title'); ?>
<?= $form->field($model, 'description')->textarea(); ?>
