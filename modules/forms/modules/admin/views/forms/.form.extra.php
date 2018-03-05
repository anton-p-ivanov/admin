<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Form $model
 * @var \app\models\Workflow $workflow
 * @var \app\widgets\form\ActiveForm $form
 */
?>

<?= $form->field($model, 'description')->textarea(); ?>
<?= $form->field($workflow, 'status')->dropDownList(\app\models\WorkflowStatus::getList()); ?>
