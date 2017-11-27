<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\FormResult $model
 * @var \app\models\Workflow $workflow
 * @var string $title
 */

use app\widgets\form\FormInput;
use forms\models\FormStatus;

?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'results-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('forms', $title); ?></div>
    </div>
    <div class="modal__body">

        <?= $form->field($model, 'status_uuid')->dropDownList(FormStatus::getList($model->form_uuid)); ?>

        <?php foreach ($model->fields as $code => $field): ?>
            <?php
            $f = $form->field($model, 'data[' . $field->code . ']');
            if ($field->isRequired()) {
                $f->options['class'] .= ' required';
            }
            ?>
            <?= $f->label($field->label)->hint($field->description)
                ->widget(FormInput::className(), ['formField' => $field]); ?>

        <?php endforeach; ?>
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