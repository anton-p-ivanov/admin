<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Course $model
 * @var \app\models\Workflow $workflow
 * @var \app\widgets\form\ActiveForm $form
 */
?>

<?= $form->field($model, 'code'); ?>
<?= $form->field($model, 'sort'); ?>
<?= $form->field($workflow, 'status')->dropDownList(
    \app\models\WorkflowStatus::getList(),
    ['dropdown' => ['class' => 'dropdown dropdown_wide dropdown_up']]
); ?>
