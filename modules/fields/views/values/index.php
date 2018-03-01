<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \fields\models\Field $field
 */

\fields\assets\ValidatorsAsset::register($this);
?>
<div class="field-title">
    <?= Yii::t('fields/values', 'Values for field'); ?> "<?= $field->label; ?>"
</div>
<div id="values-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'values-grid',
        'dataProvider' => $dataProvider,
        'columns' => require_once ".grid.php",
        'tableOptions' => ['class' => implode(' ', [
            'grid-view__table',
            'grid-view__table_fixed'
        ])],
    ]); ?>

</div>

<div class="modal modal_warning" id="confirm-modal" role="dialog" data-persistent="true">
    <?= $this->render('@app/views/layouts/confirm'); ?>
</div>