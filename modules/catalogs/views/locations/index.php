<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \catalogs\models\ElementTree $parentNode
 * @var \catalogs\models\ElementTree $currentNode
 */

$tree_uuid = Yii::$app->request->get("tree_uuid");
$updateUrl = \yii\helpers\Url::to(['index', 'tree_uuid' => $tree_uuid]);
?>

<div class="modal__container modal__container_locations">
    <div class="modal__header">
        <div class="modal__heading"><?= Yii::t('catalogs/locations', 'Select location'); ?></div>
    </div>
    <div class="modal__body">
        <div id="locations-pjax" data-pjax-container="true" data-pjax-url="<?= $updateUrl; ?>">

            <?= \yii\helpers\Html::hiddenInput('selection', \yii\helpers\Json::encode([
                'uuid' => $tree_uuid,
                'title' => \catalogs\helpers\ElementHelper::getLocationTitle([$tree_uuid])
            ])); ?>

            <?= \app\widgets\Toolbar::widget([
                'buttons' => require_once ".toolbar.php",
                'options' => ['class' => 'toolbar toolbar_light']
            ]); ?>

            <?= \app\widgets\grid\GridView::widget([
                'id' => 'locations-grid',
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => implode(' ', [
                    'grid-view__table',
                    'grid-view__table_dense',
                    'grid-view__table_light',
                    'grid-view__table_fixed'
                ])],
                'columns' => require_once ".grid.php",
                'pager' => [
                    'class' => \app\widgets\grid\Pager::className(),
                    'options' => ['class' => 'pager pager_light']
                ]
            ]); ?>

        </div>
    </div>
    <div class="modal__footer">
        <?= \yii\helpers\Html::button(Yii::t('app', 'Select'), [
            'class' => 'btn btn_primary',
            'disabled' => true,
            'data-toggle' => 'select'
        ]); ?>
        <?= \yii\helpers\Html::button(Yii::t('app', 'Close'), [
            'class' => "btn btn_default",
            'data-dismiss' => 'modal'
        ]); ?>
    </div>
</div>