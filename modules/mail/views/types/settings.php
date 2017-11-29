<?php
/**
 * @var \yii\web\View $this
 * @var TypeSettings $model
 */

use app\widgets\form\ActiveForm;
use mail\models\TypeSettings;
use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'settings-form',
        'data-type' => 'active-form'
    ]]); ?>

    <?= Html::hiddenInput('action', 'apply'); ?>

    <div class="modal__body">
        <div class="modal__heading"><?= Yii::t('mail', 'Settings'); ?></div>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'sortBy')->dropDownList(TypeSettings::getSortFields()); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'sortOrder')->dropDownList(TypeSettings::getSortOrder()); ?>
            </div>
        </div>
        <?= $form->field($model, 'showDescription')->checkbox(); ?>
    </div>
    <div class="modal__footer">
        <div class="grid__item">
            <?= Html::submitButton(Yii::t('app', 'Reset'), [
                'value' => 'reset',
                'class' => 'btn btn_default'
            ]); ?>
        </div>
        <div class="grid__item">
            <?= Html::submitButton(Yii::t('app', 'Update'), [
                'value' => 'apply',
                'class' => 'btn btn_primary'
            ]); ?>
            <?= Html::button(Yii::t('app', 'Close'), [
                'class' => 'btn btn_default',
                'data-dismiss' => 'modal'
            ]); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>