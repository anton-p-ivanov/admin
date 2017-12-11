<?php
/**
 * @var \yii\web\View $this
 * @var UserFilter $model
 */

use app\widgets\form\ActiveForm;
use users\models\UserFilter;
use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin(['options' => [
        'id' => 'filter-form',
        'data-type' => 'active-form'
    ]]); ?>

    <?= Html::hiddenInput('action', 'apply'); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('users', 'Filter'); ?></div>
    </div>
    <div class="modal__body">

        <?= $form->field($model, 'owner')->dropDownList(UserFilter::getOwners()); ?>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'fullname'); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'email'); ?>
            </div>
        </div>
        <?= $form->field($model, 'account_uuid')->dropDownList([], [
            'value' => $model->account ? $model->account->title : null,
            'data-type-ahead' => 'true',
            'data-remote' => 'true',
            'data-url' => \yii\helpers\Url::to(['accounts/list'])
        ]); ?>
        <div class="grid">
            <div class="grid__item">
                <?= $form->field($model, 'role')->dropDownList(UserFilter::getRoles()); ?>
            </div>
            <div class="grid__item">
                <?= $form->field($model, 'site')->dropDownList(UserFilter::getSites()); ?>
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