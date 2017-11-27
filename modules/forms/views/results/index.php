<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \forms\models\Form $form
 */

$updateUrl = \yii\helpers\Url::to(['results/index', 'form_uuid' => $form->uuid]);
?>
<div id="results-pjax" data-pjax-container="true" data-pjax-url="<?= $updateUrl; ?>">
<?php if ($dataProvider->totalCount > 0): ?>

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
        'options' => ['class' => 'toolbar toolbar_light'],
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'results-grid',
        'dataProvider' => $dataProvider,
        'layout' => '{items}{pager}',
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
            <?php if (!$form->fields || !$form->statuses): ?>
                <p>There are no results yet. To add a new one please add at least one field and status first.</p>
                <p><?= \yii\helpers\Html::a('Refresh', $updateUrl, [
                        'class' => 'btn btn_primary',
                    ]); ?></p>
            <?php else: ?>
                <p>There are no results yet. You can add a new one by clicking a button below.</p>
                <p><?= \yii\helpers\Html::a('Add new result', ['results/create', 'form_uuid' => $form->uuid], [
                        'class' => 'btn btn_primary',
                        'data-toggle' => 'modal',
                        'data-target' => '#results-modal',
                        'data-reload' => 'true',
                        'data-pjax' => 'false',
                        'data-persistent' => 'true'
                    ]); ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
</div>