<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\modules\admin\modules\fields\models\Field $model
 * @var \app\widgets\form\ActiveForm $form
 */

use catalogs\modules\admin\modules\fields\models\Group;

?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'default'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'sort'); ?>
    </div>
</div>
<?= $form->field($model, 'group_uuid')->dropDownList(Group::getList($model->catalog_uuid)); ?>
<?= $form->field($model, 'options', ['options' => ['class' => 'form-group form-group_text']])->textarea(); ?>