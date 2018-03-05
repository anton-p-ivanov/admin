<?php
/**
 * @var \yii\web\View $this
 * @var \forms\modules\admin\models\FormStatus $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>

<?= $form->field($model, 'default')->switch(); ?>
<?= $form->field($model, 'title'); ?>
<?= $form->field($model, 'description')->textarea(); ?>
