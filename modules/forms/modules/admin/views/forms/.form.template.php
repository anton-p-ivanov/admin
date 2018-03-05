<?php
/**
 * @var \yii\web\View $this
 * @var \forms\models\Form $model
 * @var \app\widgets\form\ActiveForm $form
 */

use yii\helpers\Html;

?>

<?= $form->field($model, 'template_active')->switch(); ?>
<?= $form->field($model, 'template')->textarea(); ?>
<div class="text_small">
    <?= Html::a(Yii::t('forms', 'List of form fields codes'), ['help', 'form_uuid' => $model->uuid], [
        'data-toggle' => 'modal',
        'data-target' => '#template-help',
    ]); ?>
</div>
