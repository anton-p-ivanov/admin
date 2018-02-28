<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\models\ElementField $model
 */

use app\widgets\form\ActiveForm;
use app\widgets\form\FormInput;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'property-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('catalogs/properties', 'Update property value'); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'value')
            ->label($model->field->label)
            ->hint($model->field->description)
            ->cleanButton()
            ->widget(FormInput::class, ['formField' => $model->field]); ?>
    </div>
    <div class="modal__footer grid">
        <div class="grid__item">

        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>