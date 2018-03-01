<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\UserRole $model
 * @var string $title
 */

use app\widgets\form\ActiveForm;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'roles-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('users', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'item_name')->dropDownList(\app\models\AuthItem::getRoles()); ?>
        <?= $form->field($model, 'valid_dates')->rangeInput(['valid_from_date', 'valid_to_date']); ?>
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