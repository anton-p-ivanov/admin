<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \forms\modules\admin\models\Form $form
 * @var array $validators
 * @var array $values
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('forms', \forms\Module::$title),
    Yii::t('fields', 'Fields')
);

// Registering assets
\fields\assets\FieldsAsset::register($this);
\forms\modules\admin\modules\fields\assets\FieldsAsset::register($this);

?>
<div class="form-title">
    <?= Yii::t('forms/fields', 'Fields for form'); ?> "<?= $form->title; ?>"
</div>
<div id="fields-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'fields-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once Yii::getAlias('@fields/views/fields/.grid.php'),
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<div class="modal modal_warning" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>