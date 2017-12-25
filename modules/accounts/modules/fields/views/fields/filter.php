<?php
/**
 * @var \yii\web\View $this
 * @var FieldFilter $model
 */

use app\widgets\form\ActiveForm;
use accounts\modules\fields\models\FieldFilter;
use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'filter-form',
        'data-type' => 'active-form'
    ]]); ?>

    <?= Html::hiddenInput('action', 'apply'); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('fields', 'Filter'); ?></div>
    </div>
    <div class="modal__body">

        <?= $form->field($model, 'owner')->dropDownList(FieldFilter::getOwners()); ?>
        <?= $form->field($model, 'type')->dropDownList(FieldFilter::getTypes()); ?>
        <?= $form->field($model, 'active')->switch(); ?>
        <?= $form->field($model, 'multiple')->switch(); ?>
        <?= $form->field($model, 'list')->switch(); ?>

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