<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\modules\admin\models\Catalog $model
 * @var \yii\widgets\ActiveForm $form
 */
?>
<?= $form->field($model, 'code'); ?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'trade')->switch(); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'index')->switch(); ?>
    </div>
</div>
