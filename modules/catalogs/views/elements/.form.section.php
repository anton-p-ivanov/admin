<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\models\Element $model
 * @var \yii\widgets\ActiveForm $form
 */

use catalogs\helpers\ElementHelper;
use yii\helpers\Html;

?>

<div class="form-group">
    <div class="input-group">
        <?= Html::activeLabel($model, 'locations', ['class' => 'form-group__label']); ?>
        <?= Html::activeTextInput($model, 'locations[0]', [
            'class' => 'form-group__input',
            'readonly' => 'true',
            'value' => ElementHelper::getLocationTitle($model->locations),
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

<?= $form->field($model, 'title'); ?>
<?= $form->field($model, 'description')->textarea(); ?>
