<?php
/**
 * @var \yii\web\View $this
 * @var TypeFilter $model
 */

use app\widgets\form\ActiveForm;
use mail\models\TypeFilter;
use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'filter-form',
        'data-type' => 'active-form'
    ]]); ?>

    <?= Html::hiddenInput('action', 'apply'); ?>

    <div class="modal__body">
        <div class="modal__heading"><?= Yii::t('mail', 'Filter'); ?></div>

        <?= $form->field($model, 'owner')->dropDownList(TypeFilter::getOwners()); ?>
        <?= $form->field($model, 'title'); ?>
        <?= $form->field($model, 'code'); ?>

    </div>
    <div class="modal__footer">
        <div class="grid__item">
            <?= Html::resetButton(Yii::t('app', 'Reset'), [
                'value' => 'reset',
                'class' => 'btn btn_default'
            ]); ?>
        </div>
        <div class="grid__item">
            <?= Html::submitButton(Yii::t('app', 'Apply'), [
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