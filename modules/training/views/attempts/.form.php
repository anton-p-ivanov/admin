<?php
/**
 * @var \yii\web\View $this
 * @var \training\models\Attempt $model
 * @var string $title
 */
?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'attempts-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('training/attempts', $title); ?></div>
    </div>
    <div class="modal__body">

        <?= $form->field($model, 'user_uuid')->dropDownList([], [
            'value' => $model->user ? $model->user->fullname . ' (' . $model->user->email . ')' : null,
            'data-type-ahead' => 'true',
            'data-remote' => 'true',
            'data-url' => \yii\helpers\Url::to(['/users/users/list'])
        ]); ?>
        <?= $form->field($model, 'dates')->rangeInput(['begin_date', 'end_date']); ?>

    </div>
    <div class="modal__footer">
        <div class="grid__item text_small">
            <?= Yii::t('app', 'Fields marked with * are mandatory'); ?>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php \app\widgets\form\ActiveForm::end(); ?>

</div>