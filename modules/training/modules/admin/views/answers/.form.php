<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Answer $model
 * @var string $title
 */

use app\widgets\form\ActiveForm;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'answers-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('training/answers', $title); ?></div>
    </div>
    <div class="modal__body">
        <?php if ($model->question->type === \training\models\Question::TYPE_SINGLE): ?>
            <div class="alert alert_warning modal__alert">
                <?= Yii::t('training/answers', 'Only one correct answer is allowable for selected question.'); ?>
            </div>
        <?php endif; ?>
        <?= $form->field($model, 'valid')->switch(); ?>
        <?= $form->field($model, 'answer')->multilineInput(); ?>
        <?= $form->field($model, 'sort'); ?>
    </div>
    <div class="modal__footer grid">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('training/answers', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>