<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\models\Element $model
 * @var \app\models\Workflow $workflow
 * @var \yii\widgets\ActiveForm $form
 */

use app\models\Site;
use app\models\WorkflowStatus;

?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'sort'); ?>
        <?= $form->field($model, 'code'); ?>
        <?= $form->field($workflow, 'status')->dropDownList(WorkflowStatus::getList()); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'sites')->checkboxList(Site::getList()); ?>
    </div>
</div>
