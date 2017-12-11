<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\UserData $model
 */

use app\widgets\form\FormInput;

?>

<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'data-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('users', 'Edit user fields'); ?></div>
    </div>
    <div class="modal__body">

        <?php foreach ($model->getFields() as $code => $field): ?>
            <?php
            $f = $form->field($model, 'data[' . $field->code . ']');
            if ($field->isRequired()) {
                $f->options['class'] .= ' required';
            }
            ?>
            <?= $f
                ->label($field->label)
                ->hint($field->description)
                ->cleanButton()
                ->widget(FormInput::className(), ['formField' => $field]); ?>

        <?php endforeach; ?>
    </div>
    <div class="modal__footer">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('forms', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php \app\widgets\form\ActiveForm::end(); ?>

</div>