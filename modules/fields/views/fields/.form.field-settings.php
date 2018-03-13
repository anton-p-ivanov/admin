<?php
/**
 * @var \yii\web\View $this
 * @var \fields\models\Field $model
 * @var \app\widgets\form\ActiveForm $form
 */

use fields\models\Field;
use fields\models\Group;

?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'multiple')->switch(); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'list')->switch(); ?>
    </div>
</div>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'type')->dropDownList(Field::getTypes()); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'default'); ?>
    </div>
</div>

<?php if ($model->hasAttribute('group_uuid')): ?>
    <?= $form->field($model, 'group_uuid')->dropDownList(Group::getList()); ?>
<?php endif; ?>