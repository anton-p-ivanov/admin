<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Course $model
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
</div>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'limit_percent'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'limit_value'); ?>
    </div>
</div>
<?= $form->field($model, 'limit_questions'); ?>
