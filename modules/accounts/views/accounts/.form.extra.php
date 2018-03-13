<?php
/**
 * @var \yii\web\View $this
 * @var \accounts\models\Account $model
 * @var \yii\widgets\ActiveForm $form
 */
?>
<?= $form->field($model, 'sort'); ?>
<div class="grid">
    <div class="grid__item">
        <?= $form->field($model, 'notify')->switch(); ?>
    </div>
    <div class="grid__item">
        <div class="form-group form-group_switch">
            <?= $this->render('code', ['model' => $model]); ?>
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