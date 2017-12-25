<?php
/**
 * @var \yii\web\View $this
 * @var \app\models\Address $model
 * @var string $title
 */
?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'addresses-form',
        'data-type' => 'active-form',
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('addresses', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'type_uuid')->dropDownList(\app\models\AddressType::getList()); ?>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'country_code')->dropDownList([], [
                    'value' => $model->country ? $model->country->title : null,
                    'data-type-ahead' => 'true',
                    'data-remote' => 'true',
                    'data-url' => \yii\helpers\Url::to(['countries/list']),
                ]); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'region'); ?>
            </div>
        </div>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'district'); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'city'); ?>
            </div>
        </div>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'zip'); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'address'); ?>
            </div>
        </div>
    </div>
    <div class="modal__footer grid">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('addresses', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php \app\widgets\form\ActiveForm::end(); ?>

</div>