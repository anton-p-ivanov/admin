<?php
/**
 * @var \yii\web\View $this
 */

use yii\helpers\Html;

?>
<div class="modal__container">

    <?= Html::beginForm(\yii\helpers\Url::current(), 'post', [
        'id' => 'confirm-form',
        'data-type' => 'active-form'
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading">Требуется подтверждение</div>
    </div>
    <div class="modal__body">
        <p>Пожалуйста, введите Ваш пароль, чтобы подтвердить действие:</p>
        <!-- Next field is required to workaround google chrome autocomplete issue -->
        <?= \yii\helpers\Html::textInput(null, null, ['style' => 'display:none']); ?>
        <!-- General password field here -->
        <div class="form-group">
            <?= Html::label('Пароль', null, ['class' => 'form-group__label']); ?>
            <?= \yii\helpers\Html::passwordInput('password', null, [
                'class' => 'form-group__input',
                'id' => 'password'
            ]); ?>
            <div class="form-group__error"></div>
        </div>
    </div>
    <div class="modal__footer text_center">
        <button type="submit" class="btn btn_primary">Подтвердить</button>
        <button type="button" class="btn btn_default" data-dismiss="modal">Отменить</button>
    </div>

    <?= Html::endForm(); ?>

</div>