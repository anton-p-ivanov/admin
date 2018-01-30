<?php
/**
 * @var \yii\web\View $this
 * @var \fields\models\FieldValidator $model
 * @var string $title
 */

use app\widgets\form\ActiveForm;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'validators-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('fields', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'active')->switch(); ?>
        <?= $form->field($model, 'type')->dropDownList(\fields\models\FieldValidator::getTypes()); ?>
        <?= $form->field($model, 'options')->textarea(); ?>
        <?= $form->field($model, 'sort'); ?>
    </div>
    <div class="modal__footer grid">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('fields', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>