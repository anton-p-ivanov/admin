<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\modules\admin\models\Catalog $model
 * @var \yii\widgets\ActiveForm $form
 */

use catalogs\modules\admin\models\Type;

?>

<?= $form->field($model, 'active')->switch(); ?>
<?= $form->field($model, 'type_uuid')->dropDownList(Type::getList()); ?>

<?= \app\widgets\form\FieldSelector::widget([
    'form' => $form,
    'model' => $model,
    'attributes' => \i18n\models\Language::getLangAttributeNames('title'),
    'options' => [
        'fieldType' => 'textInput',
        'action-icon' => 'language'
    ]
]); ?>

<?= \app\widgets\form\FieldSelector::widget([
    'form' => $form,
    'model' => $model,
    'attributes' => \i18n\models\Language::getLangAttributeNames('description'),
    'options' => [
        'fieldType' => 'textarea',
        'action-icon' => 'language'
    ]
]); ?>