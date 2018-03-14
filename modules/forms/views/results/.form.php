<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Result $model
 * @var \app\models\Workflow $workflow
 * @var string $title
 */

use forms\models\FormStatus;

?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'results-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('forms/results', $title); ?></div>
    </div>
    <div class="modal__body">

        <?= $form->field($model, 'status_uuid')->dropDownList(FormStatus::getList($model->form_uuid)); ?>

    </div>
    <div class="modal__footer">
        <div class="grid__item text_small">
            * <?= Yii::t('app', 'Fields marked with * are mandatory'); ?>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php \app\widgets\form\ActiveForm::end(); ?>

</div>