<?php
/**
 * @var \yii\web\View $this
 * @var Field $model
 * @var \app\widgets\form\ActiveForm $form
 */

use forms\modules\fields\models\Field;

?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'active')->switch(); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'multiple')->switch(); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'list')->switch(); ?>
    </div>
</div>

<?= $form->field($model, 'label'); ?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'code'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'type')->dropDownList($model->getTypes()); ?>
    </div>
</div>

<?= $form->field($model, 'description')->multilineInput(); ?>

<div class="form-group__required form-group__hint">
    * <?= Yii::t('fields', 'Required fields'); ?>
</div>
