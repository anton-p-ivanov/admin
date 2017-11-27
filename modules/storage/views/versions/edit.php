<?php
/**
 * @var \yii\web\View $this
 * @var \storage\models\StorageFile $model
 */

use app\widgets\form\ActiveForm;

?>

<div class="modal__container">

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'version-form',
        'data-type' => 'active-form'
    ]]); ?>

    <div class="modal__body">
        <div class="modal__heading"><?= Yii::t('storage', 'Rename file'); ?></div>
        <?= $form->field($model, 'name')->cleanButton(); ?>
        <div class="checkbox-group">
            <?= $form->field($model, 'useTranslit')->checkbox(); ?>
            <?= $form->field($model, 'useUnderscore')->checkbox(); ?>
        </div>
    </div>
    <div class="modal__footer">
        <button type="submit" class="btn btn_primary"><?= Yii::t('storage', 'Rename'); ?></button>
        <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>