<?php
/**
 * @var \yii\web\View $this
 * @var storage\models\Storage $model
 * @var \app\widgets\form\ActiveForm $form
 */

use storage\helpers\StorageHelper;
use yii\helpers\Html;

?>

<?php if ($model->isDirectory()): ?>
    <?= $form->field($model, 'title')->cleanButton(); ?>
<?php else: ?>
    <?= $form->field($model, 'title', ['options' => ['class' => 'form-group disabled']])
        ->textInput(['disabled' => true, 'value' => $model->file ? $model->file->name : null])
        ->cleanButton(); ?>
<?php endif; ?>

<?= $form->field($model, 'description')->multilineInput()->cleanButton(); ?>

<div class="form-group">
    <div class="input-group">
        <?= Html::activeLabel($model, 'locations', ['class' => 'form-group__label']); ?>
        <?= Html::activeTextInput($model, 'locations[0]', [
            'class' => 'form-group__input',
            'readonly' => 'true',
            'value' => $model->locations ? StorageHelper::getLocationTitle($model->locations) : 'Media library'
        ]); ?>
        <?= Html::activeHiddenInput($model, 'locations[0]'); ?>
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

<div class="form-group__required form-group__hint">
    * <?= Yii::t('storage', 'Required fields'); ?>
</div>