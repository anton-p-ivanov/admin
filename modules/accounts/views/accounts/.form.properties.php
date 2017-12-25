<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\Account $model
 * @var \yii\widgets\ActiveForm $form
 */

?>


<?= $form->field($model, 'title'); ?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'web'); ?>
        <?= $form->field($model, 'email'); ?>
        <?= $form->field($model, 'phone'); ?>
    </div>
    <div class="grid__item">
        <?= $form->field($model, 'active')->switch(); ?>
        <?= $form->field($model, 'notify')->switch(); ?>
        <div class="form-group">
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
            <?= \yii\helpers\Html::a('<i class="material-icons">refresh</i>', ['accounts/code', 'account_uuid' => $model->uuid], [
                'class' => 'code-reissue' . (!$model->accountCode ? ' code-reissue_inactive' : ''),
                'title' => Yii::t('accounts', 'Issue new registration code.'),
                'data-confirm' => 'true',
                'data-toggle' => 'action',
                'data-http-method' => 'put'
            ]); ?>
        </div>
    </div>
</div>



