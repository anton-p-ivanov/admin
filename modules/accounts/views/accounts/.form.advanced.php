<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\Account $model
 * @var \yii\widgets\ActiveForm $form
 */

use accounts\models\Type;
use app\models\Site;

?>
<?= $form->field($model, 'parent_uuid')->dropDownList([], [
    'value' => $model->parent ? $model->parent->title : null,
    'data-type-ahead' => 'true',
    'data-remote' => 'true',
    'data-url' => \yii\helpers\Url::to(['accounts/list'])
]); ?>

<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'types')->checkboxList(Type::getList()); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'sites')->checkboxList(Site::getList()); ?>
    </div>
</div>