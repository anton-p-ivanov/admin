<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Lesson $model
 * @var \app\models\Workflow $workflow
 * @var \app\widgets\form\ActiveForm $form
 */

use app\models\WorkflowStatus;

?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'code'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'sort'); ?>
    </div>
</div>

<?= $form->field($workflow, 'status')->dropDownList(WorkflowStatus::getList()); ?>
