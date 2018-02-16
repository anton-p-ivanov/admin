<?php
/**
 * @var \yii\web\View $this
 * @var \catalogs\models\Catalog[] $catalogs
 * @var \catalogs\modules\admin\models\Type $type
 */
$this->title = sprintf('%s — %s: %s',
    Yii::t('app', 'Control panel'),
    Yii::t('catalogs', 'Catalogs'),
    $type->title
);

\app\themes\material\assets\PageAsset::register($this);
?>

<div class="catalogs">
<?php foreach ($catalogs as $catalog): ?>
    <div class="catalog">
        <div class="catalog__title"><?= $catalog->title; ?></div>
        <div class="catalog__description"><?= $catalog->description ?: '<em>' . Yii::t('catalogs', 'No description') . '</em>'; ?></div>
        <div class="catalog_actions">
            <a class="btn btn_primary btn_inverted" href="<?= \yii\helpers\Url::to(['elements/index', 'tree_uuid' => $catalog->tree_uuid]); ?>">
                <?= Yii::t('catalogs', 'Elements'); ?>
            </a>
        </div>
    </div>
<?php endforeach; ?>
</div>
