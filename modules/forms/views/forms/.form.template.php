<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Form $model
 * @var \app\widgets\form\ActiveForm $form
 * @var \mail\models\Type $eventClassName
 */

$eventClassName = '\mail\models\Type';
?>

<?= $form->field($model, 'template_active')->switch(); ?>
<?= $form->field($model, 'template', ['options' => ['class' => 'form-group form-group_text']])->textarea(); ?>

<?= $this->render('.form.template.help.php'); ?>

<?php if (class_exists($eventClassName)): ?>
    <?= $form->field($model, 'event')->dropDownList($eventClassName::getList()); ?>
<?php endif; ?>
