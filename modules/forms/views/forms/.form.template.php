<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Form $model
 * @var \app\widgets\form\ActiveForm $form
 */

?>

<?= $form->field($model, 'template_active')->switch(); ?>
<?= $form->field($model, 'template', ['options' => ['class' => 'form-group form-group_text']])->textarea(); ?>

<?= $this->render('.form.template.help.php'); ?>