<?php
/**
 * @var \yii\web\View $this
 * @var \mail\models\Template $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>
<?= $form->field($model, 'type')->dropDownList(\mail\models\Type::getList()); ?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'to'); ?>
    </div>
    <div class="grid__item">
        <?= \app\widgets\form\FieldSelector::widget([
            'form' => $form,
            'model' => $model,
            'attributes' => ['from', 'reply_to', 'copy']
        ]); ?>
    </div>
</div>
<?= $form->field($model, 'subject'); ?>

<div class="form-group__required form-group__hint">
    * <?= Yii::t('mail', 'Required fields'); ?>
</div>