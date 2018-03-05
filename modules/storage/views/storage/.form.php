<?php
/**
 * @var \yii\web\View $this
 * @var string $title
 * @var storage\models\Storage $model
 */

use storage\helpers\StorageHelper;
use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'storage-form',
        'data-type' => 'active-form'
    ]]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= $title; ?></div>
    </div>
    <div class="modal__body">

        <?php if ($model->isDirectory()): ?>
            <?= $form->field($model, 'title'); ?>
        <?php else: ?>
            <?= $form->field($model, 'title')->textInput([
                'disabled' => true,
                'value' => $model->file ? $model->file->name : null
            ]); ?>
        <?php endif; ?>

        <div class="form-group">
            <div class="input-group">
                <?= Html::activeLabel($model, 'locations', ['class' => 'form-group__label']); ?>
                <?= Html::activeTextInput($model, 'locations[0]', [
                    'class' => 'form-group__input',
                    'readonly' => 'true',
                    'value' => $model->locations ? StorageHelper::getLocationTitle($model->locations) : 'Media library'
                ]); ?>
                <?= Html::activeHiddenInput($model, 'locations[0]', ['id' => false]); ?>
                <div class="input-group__buttons">
                    <?= Html::a('<i class="material-icons">apps</i>',
                        ['locations/index', 'tree_uuid' => $model->locations[0]],
                        [
                            'class' => 'input-group__button',
                            'data-toggle' => 'modal',
                            'data-target' => '#locations-modal',
                            'data-reload' => 'true'
                        ]
                    ); ?>
                    <?= Html::a('<i class="material-icons">close</i>', '#', [
                        'class' => 'input-group__button',
                        'data-toggle' => 'locations-clear'
                    ]); ?>
                </div>
            </div>
            <?= Html::activeHint($model, 'locations', ['class' => 'form-group__hint']); ?>
            <?= Html::error($model, 'locations', ['class' => 'form-group__error']); ?>
        </div>

        <?= $form->field($model, 'description')->textarea(); ?>

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

    <?php \app\widgets\form\ActiveForm::end(); ?>

</div>