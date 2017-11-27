<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var string $field_uuid
 */

$updateUrl = \yii\helpers\Url::to(['validators/index', 'field_uuid' => $field_uuid]);
?>
<div id="validators-pjax" data-pjax-container="true" data-pjax-url="<?= $updateUrl; ?>">
    <?php if ($dataProvider->totalCount > 0): ?>

        <?= \app\widgets\Toolbar::widget([
            'buttons' => require_once ".toolbar.php",
            'options' => ['class' => 'toolbar toolbar_light'],
        ]); ?>

        <?= \app\widgets\grid\GridView::widget([
            'id' => 'validators-grid',
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'tableOptions' => ['class' => implode(' ', [
                'grid-view__table',
                'grid-view__table_dense',
                'grid-view__table_light',
                'grid-view__table_fixed'
            ])],
            'columns' => require_once ".grid.php",
        ]); ?>

    <?php else: ?>
        <div class="grid-view__empty">
            <div class="grid-view__empty-content">
                <p>There are no validators yet. You can add a new one by clicking a button below.</p>
                <p><?= \yii\helpers\Html::a('Add new validator', ['validators/create', 'field_uuid' => $field_uuid], [
                        'class' => 'btn btn_primary',
                        'data-toggle' => 'modal',
                        'data-target' => '#validators-modal',
                        'data-reload' => 'true',
                        'data-pjax' => 'false',
                        'data-persistent' => 'true'
                    ]); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>