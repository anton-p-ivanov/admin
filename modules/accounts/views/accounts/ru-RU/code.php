<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\Account $model
 * @var \yii\widgets\ActiveForm $form
 */
?>
<label>Регистрационный код</label>
<div class="form-group__hint">
    <?php if ($model->accountCode): ?>
        <?php if ($model->accountCode->isValid()): ?>
            Действует до
        <?php else: ?>
            Срок действия истёк
        <?php endif; ?>
        <?= Yii::$app->formatter->asDatetime($model->accountCode->valid_date); ?>.
    <?php else: ?>
        <?php if ($model->isNewRecord): ?>
            Будет выдан после сохранения.
        <?php else: ?>
            Не установлен.
        <?php endif; ?>
    <?php endif; ?>
</div>