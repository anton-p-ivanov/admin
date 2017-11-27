<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Form $model
 * @var \app\widgets\form\ActiveForm $form
 */

?>

<?= $form->field($model, 'template_active')->switch(); ?>
<?= $form->field($model, 'template', ['options' => ['class' => 'form-group form-group_text']])->textarea(); ?>
<div class="form-group__hint">
    <p>Additional fields are available to use in template:</p>
    <ul>
        <li>{{FORM_FIELD_AUTH}} &mdash; render user authentication block</li>
        <li>{{FORM_FIELD_CAPTCHA}} &mdash; render captcha validation block</li>
    </ul>
</div>