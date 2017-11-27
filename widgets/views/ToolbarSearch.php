<?php
/**
 * @var $this \yii\web\View
 * @var $widget ToolbarSearch
 */

use app\widgets\ToolbarSearch;
use yii\helpers\Html;

$widget = $this->context;
?>

<?= Html::beginForm($widget->searchRoute, 'get', [
    'class' => 'search-form',
]); ?>

<div class="input-group">

    <?= Html::textInput('search', \Yii::$app->request->get('search'), [
        'class' => 'form-group__input input-group__input',
        'placeholder' => ToolbarSearch::t('Search ...')
    ]); ?>

    <div class="input-group__buttons">
        <?= Html::button('<i class="material-icons">search</i>', [
            'title' => ToolbarSearch::t('Search items'),
            'type' => 'submit',
            'class' => 'toolbar-btn'
        ]); ?>

        <?php if ($widget->filterEnabled): ?>
        <?= Html::a('<i class="material-icons">filter_list</i>', $widget->filterRoute, [
            'class' => 'toolbar-btn',
            'title' => ToolbarSearch::t('Configure filter rules'),
            'data-toggle' => 'modal',
            'data-target' => '#filter-modal'
        ]); ?>
        <?php endif; ?>

        <?= Html::a('<i class="material-icons">close</i>', $widget->resetRoute, [
            'data-toggle' => 'reset',
            'class' => 'toolbar-btn',
            'title' => ToolbarSearch::t('Reset search and filter rules'),
        ]); ?>

    </div>

</div>

<?= Html::endForm(); ?>