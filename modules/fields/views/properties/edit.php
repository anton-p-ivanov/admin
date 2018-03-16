<?php
/**
 * @var \yii\web\View $this
 * @var \fields\models\Property $model
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
        <div class="modal__heading"><?= Yii::t('fields/properties', 'Update property value'); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'value')
            ->label($model->field->label)
            ->hint($model->field->description)
            ->cleanButton()
            ->widget(FormInput::class, ['formField' => $model->field]); ?>
    </div>
    <div class="modal__footer">
        <div class="grid__item">
            <?php if ($model->field->type == \fields\models\Field::FIELD_TYPE_FILE): ?>
                <?= \yii\helpers\Html::a(Yii::t('app', 'Select'), ['/storage/locations/index', 'withFiles' => true], [
                    'class' => 'btn btn_default',
                    'data-toggle' => 'modal',
                    'data-target' => '#locations-modal',
                    'data-reload' => 'true'
                ]); ?>
            <?php endif; ?>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>