<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\Account $model
 * @var \yii\widgets\ActiveForm $form
 */

use app\models\Site;

?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'sites')->checkboxList(Site::getList()); ?>
    </div>
    <div class="grid__item">

    </div>
</div>
