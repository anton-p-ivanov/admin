<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var string $form_uuid
 */

$updateUrl = \yii\helpers\Url::to(['fields/index', 'form_uuid' => $form_uuid]);
?>
<div id="fields-pjax" data-pjax-container="true" data-pjax-url="<?= $updateUrl; ?>">
    <?php if ($dataProvider->totalCount > 0): ?>

        <?= \app\widgets\Toolbar::widget([
            'buttons' => require_once ".toolbar.php",
            'options' => ['class' => 'toolbar toolbar_light'],
        ]); ?>

        <?= \app\widgets\grid\GridView::widget([
            'id' => 'fields-grid',
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'tableOptions' => ['class' => implode(' ', [
                'grid-view__table',
                'grid-view__table_dense',
                'grid-view__table_light',
                'grid-view__table_fixed'
            ])],
            'columns' => require_once ".grid.php",
            'pager' => [
                'class' => 'app\widgets\grid\Pager',
                'options' => ['class' => 'pager pager_light']
            ]
        ]); ?>

    <?php else: ?>
        <div class="grid-view__empty">
            <div class="grid-view__empty-content">
                <p>There are no fields yet. You can add a new one by clicking a button below.</p>
                <p><?= \yii\helpers\Html::a('Add new field', ['fields/create', 'form_uuid' => $form_uuid], [
                        'class' => 'btn btn_primary',
                        'data-toggle' => 'modal',
                        'data-target' => '#fields-modal',
                        'data-reload' => 'true',
                        'data-pjax' => 'false',
                        'data-persistent' => 'true'
                    ]); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>