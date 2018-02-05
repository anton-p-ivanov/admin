<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \forms\modules\admin\models\Form $form
 * @var \forms\modules\admin\models\FormStatus $defaultStatus
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('forms', \forms\Module::$title),
    Yii::t('forms/statuses', 'Statuses')
);

// Registering assets
\forms\modules\admin\assets\StatusesAsset::register($this);

?>
<div class="form-title">
    <?= Yii::t('forms/statuses', 'Statuses for form'); ?> "<?= $form->title; ?>"
</div>
<div id="statuses-pjax" data-pjax-container="true">
    <?php if (!$defaultStatus): ?>
        <div class="alert alert_warning">
            <?= Yii::t('forms/statuses', 'There is no default status. Default status is required for web form.'); ?>
        </div>
    <?php endif; ?>

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'statuses-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<div class="modal" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>