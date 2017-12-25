<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\AccountContact $model
 * @var string $title
 */
?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'contacts-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('contacts', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'user_uuid')->dropDownList([], [
            'value' => $model->user ? $model->user->fullname : null,
            'data-type-ahead' => 'true',
            'data-remote' => 'true',
            'data-url' => \yii\helpers\Url::to(['/users/users/list'])
        ]); ?>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'fullname')->textInput([
                    'readonly' => $model->hasUser(),
                    'value' => $model->hasUser() ? $model->user->fullname : $model->fullname
                ]); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'email')->textInput([
                    'readonly' => $model->hasUser(),
                    'value' => $model->hasUser() ? $model->user->email : $model->email
                ]); ?>
            </div>
        </div>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'position'); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'sort'); ?>
            </div>
        </div>
    </div>
    <div class="modal__footer grid">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('contacts', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php \app\widgets\form\ActiveForm::end(); ?>

</div>