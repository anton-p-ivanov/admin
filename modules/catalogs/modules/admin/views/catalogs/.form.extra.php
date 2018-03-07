<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\modules\admin\models\Catalog $model
 * @var \yii\widgets\ActiveForm $form
 */
?>
<?= $form->field($model, 'index')->switch(); ?>

<?= \app\widgets\form\FieldSelector::widget([
    'form' => $form,
    'model' => $model,
    'attributes' => \i18n\models\Language::getLangAttributeNames('description'),
    'options' => [
        'fieldType' => 'textarea',
        'action-icon' => 'language'
    ]
]); ?>
<?= $form->field($model, 'code'); ?>

