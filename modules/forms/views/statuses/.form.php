<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\FormStatus $model
 * @var \app\models\Workflow $workflow
 * @var string $title
 */

use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'statuses-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__body">
        <div class="modal__heading"><?= Yii::t('forms', $title); ?></div>

        <?= Html::activeHiddenInput($model, 'form_uuid'); ?>

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