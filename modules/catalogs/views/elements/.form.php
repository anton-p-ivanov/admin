<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\models\Element $model
 * @var \app\models\Workflow $workflow
 * @var string $title
 */

use app\widgets\form\ActiveForm;
use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'elements-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('catalogs/elements', $title); ?></div>
    </div>
    <div class="modal__body">
        <?php if ($model->isSection()): ?>
            <?= $this->render('.form.section.php', [
                'model' => $model,
                'form' => $form,
                'workflow' => $workflow
            ]); ?>
        <?php else: ?>
            <?php $widget = \app\widgets\Tabs::begin([
                'items' => require_once ".form.tabs.php"
            ]); ?>
                <?php foreach ($widget->items as $index => $item): ?>
                    <?= Html::beginTag('div', [
                        'class' => 'tabs-pane' . ($item['active'] === true ? ' active' : ''),
                        'id' => $item['id'],
                        'data-remote' => isset($item['options']['data-remote']) ? $item['id'] : null
                    ]); ?>
                        <?= $this->render('.form.' . $item['id'] . '.php', [
                            'model' => $model,
                            'form' => $form,
                            'workflow' => $workflow
                        ]); ?>
                    <?= Html::endTag('div'); ?>
                <?php endforeach; ?>
            <?php \app\widgets\Tabs::end(); ?>
        <?php endif; ?>
    </div>
    <div class="modal__footer grid">
        <div class="grid__item">
            <div class="form-group__required form-group__hint">
                * <?= Yii::t('catalogs/elements', 'Required fields'); ?>
            </div>
        </div>
        <div class="grid__item text_right">
            <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
            <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>