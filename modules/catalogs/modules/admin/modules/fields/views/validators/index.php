<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \catalogs\modules\admin\modules\fields\models\Field $field
 */

$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('catalogs', \catalogs\Module::$title),
    Yii::t('catalogs/fields/validators', 'Validators')
);

// Registering assets
\catalogs\modules\admin\modules\fields\assets\ValidatorsAsset::register($this);

?>
<div class="field-title">
    <?= Yii::t('catalogs/fields/validators', 'Validators for field'); ?> "<?= $field->label; ?>"
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