<?php
/**
 * @var \yii\web\View $this
 * @var \sales\modules\discounts\models\Discount $model
 * @var string $title
 */

use app\widgets\form\ActiveForm;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'discounts-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('sales/discounts', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'title'); ?>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'code'); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'value'); ?>
            </div>
        </div>
    </div>
    <div class="modal__footer">
        <div class="grid__item text_small">
            <?= Yii::t('sales/discounts', 'Fields marked with * are mandatory'); ?>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>