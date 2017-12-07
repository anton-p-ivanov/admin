<?php
/**
 * @var \yii\web\View $this
 * @var \fields\models\FieldValue $model
 * @var string $title
 */
?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'values-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('fields', $title); ?></div>
    </div>
    <div class="modal__body">

        <?= $form->field($model, 'label'); ?>
        <?= $form->field($model, 'value'); ?>
        <?= $form->field($model, 'sort'); ?>

        <div class="form-group__required form-group__hint">
            * <?= Yii::t('forms', 'Required fields'); ?>
        </div>

    </div>
    <div class="modal__footer">
        <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
        <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
    </div>

    <?php \app\widgets\form\ActiveForm::end(); ?>

</div>