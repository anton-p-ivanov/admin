<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Test $model
 * @var \app\models\Workflow $workflow
 * @var \app\widgets\form\ActiveForm $form
 */
?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'limit_attempts'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'limit_time'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'limit_percent'); ?>
    </div>
</div>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'limit_value'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'limit_questions'); ?>
    </div>
</div>

