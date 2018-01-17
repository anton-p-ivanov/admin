<?php
/**
 * @var \yii\web\View $this
 * @var \i18n\modules\admin\models\Language $model
 * @var string $title
 */

use app\widgets\form\ActiveForm;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'languages-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('i18n', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'default')->switch(); ?>
        <?= $form->field($model, 'title'); ?>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'code'); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'sort'); ?>
            </div>
        </div>
    </div>
    <div class="modal__footer grid">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('i18n', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>