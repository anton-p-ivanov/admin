<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\Account $model
 * @var \yii\widgets\ActiveForm $form
 */
?>
<label>Registration code</label>
<div class="form-group__hint">
    <?php if ($model->accountCode): ?>
        <?php if ($model->accountCode->isValid()): ?>
            Valid until
        <?php else: ?>
            Expired
        <?php endif; ?>
        <?= Yii::$app->formatter->asDatetime($model->accountCode->valid_date); ?>.
    <?php else: ?>
        <?php if ($model->isNewRecord): ?>
            Will be set after saving account.
        <?php else: ?>
            Not set.
        <?php endif; ?>
    <?php endif; ?>
</div>