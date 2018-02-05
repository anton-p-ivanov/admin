<?php
/**
 * @var \yii\web\View $this
 * @var \forms\modules\admin\models\FormStatus $model
 * @var \app\models\Workflow $workflow
 * @var string $title
 * @var \mail\models\Template $templateClassName
 */

$templateClassName = '\mail\models\Template';
?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'statuses-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('forms/statuses', $title); ?></div>
    </div>
    <div class="modal__body">

        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'active')->switch(); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'default')->switch(); ?>
            </div>
        </div>

        <?= $form->field($model, 'title'); ?>
        <?= $form->field($model, 'description')->multilineInput(); ?>
        <?= $form->field($model, 'sort'); ?>

        <?php if (class_exists($templateClassName)): ?>
            <?= $form->field($model, 'mail_template_uuid')
                ->dropDownList($templateClassName::getList($model->form->{'event'})); ?>
        <?php endif; ?>

    </div>
    <div class="modal__footer grid">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('forms/statuses', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php \app\widgets\form\ActiveForm::end(); ?>

</div>