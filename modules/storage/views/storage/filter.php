<?php
/**
 * @var \yii\web\View $this
 * @var StorageFilter $model
 */

use app\widgets\form\ActiveForm;
use storage\models\StorageFilter;
use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'filter-form',
        'data-type' => 'active-form'
    ]]); ?>

    <?= Html::hiddenInput('action', 'apply'); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('storage', 'Data filter'); ?></div>
    </div>
    <div class="modal__body">

        <?= $form->field($model, 'owner')->dropDownList(StorageFilter::getOwners()); ?>
        <?= $form->field($model, 'size')->rangeInput(['min', 'max']); ?>
        <?= $form->field($model, 'type')->dropDownList(StorageFilter::getTypes()); ?>

    </div>
    <div class="modal__footer">
        <div class="grid-item">
            <?= Html::resetButton(Yii::t('app', 'Reset'), [
                'value' => 'reset',
                'class' => 'btn btn_default'
            ]); ?>
        </div>
        <div class="grid-item">
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