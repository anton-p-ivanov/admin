<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\UserAccount $model
 * @var string $title
 */

use app\widgets\form\ActiveForm;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'accounts-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('users', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'account_uuid')->dropDownList([], [
            'value' => $model->account ? $model->account->title : null,
            'data-type-ahead' => 'true',
            'data-remote' => 'true',
            'data-url' => \yii\helpers\Url::to(['accounts/list'])
        ]); ?>
        <?= $form->field($model, 'position'); ?>
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

    <?php ActiveForm::end(); ?>

</div>