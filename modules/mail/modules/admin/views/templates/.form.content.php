<?php
/**
 * @var \yii\web\View $this
 * @var \mail\models\Template $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>
<?= $form->field($model, 'format', ['inline' => true])->radioList([
    'text' => $model->getAttributeLabel('text'),
    'html' => $model->getAttributeLabel('html'),
]); ?>
<?= $form->field($model, 'text')->textarea(); ?>
<?= $form->field($model, 'html', ['options' => ['class' => 'form-group form-group_text form-group_hidden']])->textarea(); ?>
