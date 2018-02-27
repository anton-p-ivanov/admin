<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\AccountStatus $model
 * @var string $title
 */

use app\widgets\form\ActiveForm;
use partnership\models\Status;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'statuses-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('accounts/statuses', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'status_uuid')->dropDownList(Status::getList()); ?>
        <?= $form->field($model, 'dates')->rangeInput(['issue_date', 'expire_date']); ?>
    </div>
    <div class="modal__footer">
        <div class="grid__item text_small">
            <?= Yii::t('accounts/statuses', 'Fields marked with * are mandatory'); ?>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>