<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\AccountManager $model
 * @var string $title
 */
?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'managers-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('accounts/managers', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'manager_uuid')->dropDownList([], [
            'value' => $model->manager ? $model->manager->getFullName() . ' (' . $model->manager->email . ')' : null,
            'data-type-ahead' => 'true',
            'data-remote' => 'true',
            'data-url' => \yii\helpers\Url::to(['/users/users/list'])
        ]); ?>
        <?= $form->field($model, 'sort'); ?>
        <?= $form->field($model, 'comments')->textarea(); ?>
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