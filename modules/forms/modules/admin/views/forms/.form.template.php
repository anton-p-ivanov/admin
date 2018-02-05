<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Form $model
 * @var \app\widgets\form\ActiveForm $form
 * @var \mail\models\Type $eventClassName
 * @var \mail\models\Template $templateClassName
 */

$eventClassName = '\mail\models\Type';
$templateClassName = '\mail\models\Template';
?>

<?= $form->field($model, 'template_active')->switch(); ?>
<?= $form->field($model, 'template', ['options' => ['class' => 'form-group form-group_text']])->textarea(); ?>

<?= $this->render('.form.template.help.php'); ?>

<?php if (class_exists($eventClassName)): ?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'event')
            ->dropDownList($eventClassName::getList(), ['hiddenInputOptions' => [
                'data-url' => \yii\helpers\Url::to(['templates'])
            ]]); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'mail_template_uuid')
            ->dropDownList($templateClassName::getList($model->{'event'})); ?>
    </div>
</div>
<?php endif; ?>
