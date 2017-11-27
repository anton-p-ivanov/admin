<?php
/**
 * @var \yii\web\View $this
 * @var FormFilter $model
 */

use app\widgets\form\ActiveForm;
use forms\models\FormFilter;
use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'filter-form',
        'data-type' => 'active-form'
    ]]); ?>

    <?= Html::hiddenInput('action', 'apply'); ?>

    <div class="modal__body">
        <div class="modal__heading"><?= Yii::t('forms', 'Form`s filter'); ?></div>

        <?= $form->field($model, 'owner')->dropDownList(FormFilter::getOwners()); ?>
        <?= $form->field($model, 'title'); ?>
        <?= $form->field($model, 'code'); ?>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'withResults')->checkbox(); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'inUse')->checkbox(); ?>
            </div>
        </div>

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