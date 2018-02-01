<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \fields\models\Field $field
 */
?>
<div class="field-title">
    <?= Yii::t('fields/validators', 'Validators for field'); ?> "<?= $field->label; ?>"
</div>
<div id="validators-pjax" data-pjax-container="true">

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'validators-grid',
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