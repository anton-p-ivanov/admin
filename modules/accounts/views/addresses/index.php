<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \accounts\models\Account $account
 */

$updateUrl = \yii\helpers\Url::to(['addresses/index', 'account_uuid' => $account->uuid]);
?>
<div id="addresses-pjax" data-pjax-container="true" data-pjax-url="<?= $updateUrl; ?>">
<?php if ($dataProvider->totalCount > 0): ?>

    <?= \app\widgets\Toolbar::widget([
        'buttons' => require_once ".toolbar.php",
        'options' => ['class' => 'toolbar toolbar_light'],
    ]); ?>

    <?= \app\widgets\grid\GridView::widget([
        'id' => 'addresses-grid',
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
            <?= $this->render('.index.empty.php', ['account_uuid' => $account->uuid]); ?>
        </div>
    </div>
<?php endif; ?>
</div>