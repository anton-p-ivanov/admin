<?php
/**
 * @var \yii\web\View $this
 * @var string $title
 * @var storage\models\Storage $model
 */

use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = \app\widgets\form\ActiveForm::begin(['options' => [
        'id' => 'storage-form',
        'data-type' => 'active-form'
    ]]); ?>

    <?= Html::activeHiddenInput($model, 'type'); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= $title; ?></div>
    </div>
    <div class="modal__body">

        <?php if ($model->isDirectory()): ?>
            <?= $this->render('.form.properties.php', [
                'model' => $model,
                'form' => $form
            ]); ?>
        <?php else: ?>
            <?php $widget = \app\widgets\Tabs::begin(['items' => require_once ".form.tabs.php"]); ?>
            <?php foreach ($widget->items as $index => $item): ?>
                <?= Html::beginTag('div', [
                    'class' => 'tabs-pane' . ($item['active'] === true ? ' active' : ''),
                    'id' => $item['id'],
                    'data-remote' => isset($item['options']['data-remote']) ? $item['id'] : null
                ]); ?>
                    <?= $this->render('.form.' . $item['id'] . '.php', [
                        'model' => $model,
                        'form' => $form
                    ]); ?>
                <?= Html::endTag('div'); ?>
            <?php endforeach; ?>
            <?php \app\widgets\Tabs::end(); ?>
        <?php endif; ?>

    </div>
    <div class="modal__footer">
        <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
        <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
    </div>

    <?php \app\widgets\form\ActiveForm::end(); ?>

</div>