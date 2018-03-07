<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\modules\admin\models\Catalog $model
 * @var \yii\widgets\ActiveForm $form
 */

use catalogs\modules\admin\models\Type;

?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'active')->switch(); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'trade')->switch(); ?>
    </div>
</div>

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
