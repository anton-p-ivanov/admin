<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Form $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>
    <div class="grid">
        <div class="grid__item">
            <?= $form->field($model, 'code'); ?>
        </div>
        <div class="grid__item">
            <?= $form->field($model, 'sort'); ?>
        </div>
    </div>

<hr>

<div class="grid">
    <div class="grid__item">
    <?= $form->field($model, 'event')
        ->dropDownList(\mail\models\Type::getList(), ['hiddenInputOptions' => [
            'data-url' => \yii\helpers\Url::to(['templates'])
        ]]); ?>
    </div>
    <div class="grid__item">
    <?= $form->field($model, 'mail_template_uuid')
        ->dropDownList(\mail\models\Template::getList($model->{'event'}), [
            'disabled' => !$model->mail_template_uuid
        ]); ?>
    </div>
</div>
