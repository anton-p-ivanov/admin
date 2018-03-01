<?php
/**
 * @var \yii\web\View $this
 * @var \fields\models\Field $model
 * @var \app\widgets\form\ActiveForm $form
 */

use fields\models\Field;

?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'multiple')->switch(); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'list')->switch(); ?>
    </div>
</div>

<?= $form->field($model, 'type')->dropDownList(Field::getTypes()); ?>
<?= $form->field($model, 'default'); ?>
