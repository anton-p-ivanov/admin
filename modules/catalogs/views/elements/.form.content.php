<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\models\Element $model
 * @var \yii\widgets\ActiveForm $form
 */
?>

<?= $form->field($model, 'description')->textarea(['data-toggle' => 'editor']); ?>
<?= $form->field($model, 'content')->textarea(['data-toggle' => 'editor']); ?>