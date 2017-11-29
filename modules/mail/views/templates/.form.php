<?php
/**
 * @var \yii\web\View $this
 * @var \mail\models\Template $model
 * @var \app\models\Workflow $workflow
 * @var string $title
 */

use app\widgets\form\ActiveForm;
use yii\helpers\Html;

?>
<div class="modal__container">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'templates-form',
            'data-type' => 'active-form',
        ]
    ]); ?>

    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('mail', $title); ?></div>
    </div>
    <div class="modal__body">

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

    </div>
    <div class="modal__footer">
        <button type="submit" class="btn btn_primary"><?= Yii::t('app', $model->isNewRecord ? 'Create' : 'Update'); ?></button>
        <button type="button" class="btn btn_default" data-dismiss="modal"><?= Yii::t('app', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>