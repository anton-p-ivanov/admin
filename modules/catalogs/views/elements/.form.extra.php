<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\models\Element $model
 * @var \app\models\Workflow $workflow
 * @var \yii\widgets\ActiveForm $form
 */
?>
<?= $form->field($model, 'sort'); ?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($workflow, 'status')->radioList(\app\models\WorkflowStatus::getList()); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'sites')->checkboxList(\app\models\Site::getList()); ?>
    </div>
</div>

