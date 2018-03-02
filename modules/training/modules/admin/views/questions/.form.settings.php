<?php
/**
 * @var \yii\web\View $this
 * @var \training\modules\admin\models\Question $model
 * @var \app\widgets\form\ActiveForm $form
 */

use training\modules\admin\models\Question;

?>
<?= $form->field($model, 'type')->dropDownList(Question::getTypes()); ?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'value'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'sort'); ?>
    </div>
</div>