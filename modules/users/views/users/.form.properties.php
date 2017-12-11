<?php
/**
 * @var \yii\web\View $this
 * @var \users\models\User $model
 * @var \app\widgets\form\ActiveForm $form
 */
?>

<br>

<div class="grid">
    <div class="grid__item">
        <div class="grid">
            <div class="grid__item"><?= $form->field($model, 'fname'); ?></div>
            <div class="grid__item"><?= $form->field($model, 'lname'); ?></div>
        </div>
        <?= $form->field($model, 'email'); ?>
        <div class="text_center">
            <?= \yii\helpers\Html::a(Yii::t('users', 'Change password'), ['password', 'user_uuid' => $model->uuid], [
                'class' => 'btn btn_default',
                'data-toggle' => 'modal',
                'data-target' => '#passwords-modal',
                'data-reload' => 'true',
                'data-persistent' => 'true'
            ]); ?>
        </div>
    </div>
    <div class="grid__item">

    </div>
</div>
