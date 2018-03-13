<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\modules\admin\models\Type $model
 * @var string $title
 */

use app\widgets\form\ActiveForm;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'types-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('accounts/types', $title); ?></div>
    </div>
    <div class="modal__body">
        <?= $form->field($model, 'default')->switch(); ?>
        <div class="grid">
            <div class="grid__item">
                <?= \app\widgets\form\FieldSelector::widget([
                    'form' => $form,
                    'model' => $model,
                    'attributes' => \i18n\models\Language::getLangAttributeNames('title'),
                    'options' => [
                        'fieldType' => 'textInput',
                        'action-icon' => 'language'
                    ]
                ]); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'sort'); ?>
            </div>
        </div>
    </div>
    <div class="modal__footer">
        <div class="grid__item text_small">
            <?= Yii::t('app', 'Fields marked with * are mandatory'); ?>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>