<?php
/**
 * @var \yii\web\View $this
 * @var \yii\data\Pagination $pagination
 * @var Pager $widget
 */

use app\widgets\grid\Pager;
use yii\helpers\Html;

$widget = $this->context;
$max = ($pagination->page + 1) * $pagination->pageSize;

$currentPage = $pagination->getPage();
$pageCount = $pagination->getPageCount();

if (($prevPage = $currentPage - 1) < 0) {
    $prevPage = 0;
}
if (($nextPage = $currentPage + 1) >= $pageCount - 1) {
    $nextPage = $pageCount - 1;
}
?>
<div class="<?= $widget->options['class']; ?>">
    <div class="pager__per-page">
        <?= Pager::t('Rows per page: {size}', ['size' => $pagination->totalCount < $pagination->pageSize
            ? $pagination->totalCount
            : $pagination->pageSize
        ]); ?>
    </div>
    <div class="pager__counter">
        <?= Pager::t('{start} &ndash; {end} of {total}', [
            'start' => $currentPage * $pagination->pageSize + 1,
            'end' => $max > $pagination->totalCount ? $pagination->totalCount : $max,
            'total' => $pagination->totalCount
        ]); ?>
    </div>
    <div class="pager-nav">
        <?= Html::a('<i class="material-icons">keyboard_arrow_left</i>', $pagination->createUrl($prevPage), [
            'title' => Pager::t('Previous page'),
            'class' => 'pager-nav__item' . ($currentPage == 0 ? ' disabled' : null)
        ]); ?>
        <?= Html::a('<i class="material-icons">keyboard_arrow_right</i>', $pagination->createUrl($nextPage), [
            'title' => Pager::t('Next page'),
            'class' => 'pager-nav__item' . ($currentPage >= $pageCount - 1 ? ' disabled' : null)
        ]); ?>
    </div>
</div>
