<?php
/**
 * @var \yii\web\View $this
 * @var \mail\models\Template $model
 * @var \app\widgets\form\ActiveForm $form
 * @var \app\models\Workflow $workflow
 */

use app\models\Site;

?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'sites')->checkboxList(Site::getList()); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'code'); ?>
    </div>
</div>